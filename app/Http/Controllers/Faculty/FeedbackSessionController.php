<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Models\FeedbackSession;
use App\Models\FacultyCourse;
use App\Services\FeedbackEligibilityService;
use Illuminate\Http\Request;

class FeedbackSessionController extends Controller
{
    public function __construct(
        private FeedbackEligibilityService $eligibilityService
    ) {
    }

    protected function authorizeSession(ClassSession $classSession): void
    {
        $assigned = FacultyCourse::where('user_id', auth()->id())
            ->where('class_section_id', $classSession->class_section_id)
            ->where('is_active', true)
            ->exists();

        abort_unless($assigned || auth()->user()->isAdmin(), 403);
    }

    public function index()
    {
        $user = auth()->user();

        $sectionIds = FacultyCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('class_section_id');

        $feedbackSessions = FeedbackSession::with([
            'classSession.section.course',
            'eligibility',
        ])
            ->withCount('responses')
            ->whereHas(
                'classSession',
                fn ($query) => $query->whereIn('class_section_id', $sectionIds)
            )
            ->latest()
            ->paginate(15);

        return view('faculty.feedback.index', compact('feedbackSessions'));
    }

    public function create(ClassSession $classSession)
    {
        $this->authorizeSession($classSession);

        abort_if(
            $classSession->feedbackSession,
            400,
            'Feedback session already exists.'
        );

        return view('faculty.feedback.create', compact('classSession'));
    }

    public function store(Request $request, ClassSession $classSession)
    {
        $this->authorizeSession($classSession);

        abort_if(
            $classSession->feedbackSession,
            400,
            'Feedback session already exists.'
        );

        FeedbackSession::create([
            'class_session_id' => $classSession->id,
            'created_by' => auth()->id(),
            'status' => 'draft',
            'is_released' => false,
        ]);

        return redirect()
            ->route('faculty.feedback.index')
            ->with('success', 'Feedback session created.');
    }

    public function open(FeedbackSession $feedbackSession)
    {
        $this->authorizeSession($feedbackSession->classSession);

        abort_if(
            $feedbackSession->status === 'active',
            400,
            'This feedback session is already active.'
        );

        $feedbackSession->update([
            'status' => 'active',
            'opened_at' => now(),
            'is_released' => false,
        ]);

        return back()->with(
            'success',
            'Feedback session opened. Eligible students can now submit feedback.'
        );
    }

    public function close(FeedbackSession $feedbackSession)
    {
        $this->authorizeSession($feedbackSession->classSession);

        $feedbackSession->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        // Do not calculate or expose ratings here.
        // Admin release is the only point where faculty receives feedback.

        return back()->with(
            'success',
            'Feedback session closed and sent for administrator review.'
        );
    }

    public function analytics(FeedbackSession $feedbackSession)
    {
        $this->authorizeSession($feedbackSession->classSession);

        // Critical release protection.
        abort_unless(
            $feedbackSession->isReleased(),
            403,
            'Feedback has not been released by the administrator yet.'
        );

        $responseCount = $feedbackSession->responses()->count();
        $eligibleCount = $feedbackSession->eligibility()->count();

        $questionStats = [];

        foreach (
            $feedbackSession->responses()->with('answers.question')->get()
            as $response
        ) {
            foreach ($response->answers as $answer) {
                $questionId = $answer->feedback_question_id;

                if (! isset($questionStats[$questionId])) {
                    $questionStats[$questionId] = [
                        'question' => $answer->question->question_text,
                        'type' => $answer->question->type,
                        'weight' => $answer->question->weight,
                        'values' => [],
                        'texts' => [],
                    ];
                }

                if ($answer->rating_value !== null) {
                    $questionStats[$questionId]['values'][] = $answer->rating_value;
                }

                if (! empty($answer->text_answer)) {
                    $questionStats[$questionId]['texts'][] = $answer->text_answer;
                }
            }
        }

        foreach ($questionStats as &$stat) {
            $stat['average'] = count($stat['values']) > 0
                ? round(array_sum($stat['values']) / count($stat['values']), 2)
                : null;
        }

        $ratingResult = \App\Models\RatingResult::where(
            'class_section_id',
            $feedbackSession->classSession->class_section_id
        )
            ->where(
                'semester_id',
                $feedbackSession->classSession->section->semester_id
            )
            ->first();

        return view('faculty.feedback.analytics', compact(
            'feedbackSession',
            'questionStats',
            'responseCount',
            'eligibleCount',
            'ratingResult'
        ));
    }

    public function myRatings()
    {
        $user = auth()->user();

        $currentSemester = \App\Models\Semester::where(
            'is_current',
            true
        )->first();

        $ratingResults = \App\Models\RatingResult::where(
            'faculty_id',
            $user->id
        )
            ->with(['section.course', 'semester'])
            ->orderByDesc('calculated_at')
            ->get();

        return view(
            'faculty.feedback.my-ratings',
            compact('ratingResults', 'currentSemester')
        );
    }
}
