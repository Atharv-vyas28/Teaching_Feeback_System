<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Models\FeedbackSession;
use App\Models\FacultyCourse;
use App\Models\RatingResult;
use App\Services\FeedbackEligibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $ratingResult = RatingResult::where('feedback_session_id', $feedbackSession->id)->first();
        $responseCount = $ratingResult?->response_count ?? 0;
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
                        'question_id' => $questionId,
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

        $storedQuestionAverages = $ratingResult?->question_averages ?? [];

        foreach ($questionStats as &$stat) {
            $stat['average'] = count($stat['values']) > 0
                ? round(array_sum($stat['values']) / count($stat['values']), 2)
                : null;

            // Replace the display average with the stored attendance-weighted
            // result when it is available after Admin release.
            $stored = $storedQuestionAverages[$stat['question_id']] ?? null;
            if ($stored && array_key_exists('average', $stored)) {
                $stat['average'] = $stored['average'];
            }
        }

        $semesterId = $feedbackSession->classSession->section->semester_id;
        $facultyDepartmentScores = RatingResult::query()
            ->join('class_sections', 'rating_results.class_section_id', '=', 'class_sections.id')
            ->join('courses', 'class_sections.course_id', '=', 'courses.id')
            ->whereNotNull('rating_results.overall_weighted_rating')
            ->when($semesterId, fn ($query) => $query->where('rating_results.semester_id', $semesterId))
            ->selectRaw('courses.department_id, rating_results.faculty_id, AVG(rating_results.overall_weighted_rating) as faculty_average')
            ->groupBy('courses.department_id', 'rating_results.faculty_id');

        $departmentLeaderboard = DB::query()
            ->fromSub($facultyDepartmentScores, 'faculty_scores')
            ->join('departments', 'departments.id', '=', 'faculty_scores.department_id')
            ->selectRaw('departments.id, departments.name, departments.code, AVG(faculty_scores.faculty_average) as average_score, COUNT(*) as faculty_count')
            ->groupBy('departments.id', 'departments.name', 'departments.code')
            ->orderByDesc('average_score')
            ->get()
            ->values()
            ->map(fn ($department, $index) => [
                'rank' => $index + 1,
                'name' => $department->name,
                'code' => $department->code,
                'average_score' => round((float) $department->average_score, 2),
                'faculty_count' => (int) $department->faculty_count,
            ]);

        return view('faculty.feedback.analytics', compact(
            'feedbackSession',
            'questionStats',
            'responseCount',
            'eligibleCount',
            'ratingResult',
            'departmentLeaderboard'
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
