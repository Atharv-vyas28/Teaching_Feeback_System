<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FeedbackAnswer;
use App\Models\FeedbackEligibility;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackResponse;
use App\Models\FeedbackSession;
use App\Services\FeedbackEligibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeedbackController extends Controller
{
    public function __construct(
        private FeedbackEligibilityService $eligibilityService
    ) {
    }

    public function index()
    {
        $student = auth()->user();

        $enrolledSectionIds = DB::table('course_enrollments')
            ->where('user_id', $student->id)
            ->where('status', 'active')
            ->pluck('class_section_id');

        $feedbackSessions = FeedbackSession::with([
            'classSession.section.course',
            'classSession.section.faculty',
        ])
            ->whereHas('classSession', function ($query) use ($enrolledSectionIds) {
                $query->whereIn('class_section_id', $enrolledSectionIds);
            })
            ->orderByDesc('created_at')
            ->get();

        $available = [];
        $completed = [];
        $ineligible = [];

        foreach ($feedbackSessions as $session) {
            $eligibility = $this->eligibilityService->checkEligibility(
                $student,
                $session
            );

            $submitted = FeedbackEligibility::where(
                'feedback_session_id',
                $session->id
            )
                ->where('student_id', $student->id)
                ->where('has_submitted', true)
                ->exists();

            if ($submitted) {
                $completed[] = $session;
            } elseif ($eligibility['eligible']) {
                $available[] = $session;
            } else {
                $ineligible[] = [
                    'session' => $session,
                    'reason' => $eligibility['reason'],
                ];
            }
        }

        return view(
            'student.feedback.index',
            compact('available', 'completed', 'ineligible')
        );
    }

    public function show(FeedbackSession $feedbackSession)
    {
        $student = auth()->user();

        $eligibility = $this->eligibilityService->checkEligibility(
            $student,
            $feedbackSession
        );

        if (!$eligibility['eligible']) {
            return redirect()
                ->route('student.feedback.index')
                ->with('error', $eligibility['reason']);
        }

        $questions = FeedbackQuestion::active()
            ->ordered()
            ->get();

        if ($questions->isEmpty()) {
            return redirect()
                ->route('student.feedback.index')
                ->with('error', 'Feedback cannot be submitted because no questions are configured.');
        }

        $classSession = $feedbackSession->classSession;

        $faculty = $classSession->section
            ->faculty
            ->first();

        return view(
            'student.feedback.show',
            compact(
                'feedbackSession',
                'questions',
                'classSession',
                'faculty'
            )
        );
    }

    public function submit(
        Request $request,
        FeedbackSession $feedbackSession
    ) {
        $student = auth()->user();

        $eligibility = $this->eligibilityService->checkEligibility(
            $student,
            $feedbackSession
        );

        if (!$eligibility['eligible']) {
            return redirect()
                ->route('student.feedback.index')
                ->with('error', $eligibility['reason']);
        }

        $questions = FeedbackQuestion::active()
            ->ordered()
            ->get();

        if ($questions->isEmpty()) {
            return redirect()
                ->route('student.feedback.index')
                ->with(
                    'error',
                    'Feedback cannot be submitted because no questions are configured.'
                );
        }

        $rules = [
            'answers' => 'required|array',
        ];

        foreach ($questions as $question) {
            if ($question->type === 'rating') {
                $rules["answers.{$question->id}.rating"] =
                    $question->is_required
                    ? 'required|numeric|min:1|max:5'
                    : 'nullable|numeric|min:1|max:5';
            }

            if ($question->type === 'text') {
                $rules["answers.{$question->id}.text"] =
                    $question->is_required
                    ? 'required|string|max:1000'
                    : 'nullable|string|max:1000';
            }
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $feedbackSession, $student, $questions) {
            $eligibilityRecord = FeedbackEligibility::where(
                'feedback_session_id',
                $feedbackSession->id
            )
                ->where('student_id', $student->id)
                ->lockForUpdate()
                ->first();

            if ($eligibilityRecord?->has_submitted) {
                abort(
                    422,
                    'You have already submitted feedback for this session.'
                );
            }

            /*
             * The anonymous token connects the private
             * eligibility record with the anonymous response.
             */
            $anonymousToken =
                $eligibilityRecord?->anonymous_token
                ?? Str::uuid()->toString();

            if ($eligibilityRecord) {
                $eligibilityRecord->update([
                    'anonymous_token' => $anonymousToken,
                    'has_submitted' => true,
                    'submitted_at' => now(),
                    'included_in_score' => false,
                    'attendance_weight' => null,
                ]);
            } else {
                $eligibilityRecord = FeedbackEligibility::create([
                    'feedback_session_id' =>
                        $feedbackSession->id,

                    'student_id' =>
                        $student->id,

                    'anonymous_token' =>
                        $anonymousToken,

                    'has_submitted' =>
                        true,

                    'included_in_score' =>
                        false,

                    'attendance_weight' =>
                        null,

                    'submitted_at' =>
                        now(),
                ]);
            }

            $response = FeedbackResponse::create([
                'feedback_session_id' =>
                    $feedbackSession->id,

                'anonymous_token' =>
                    $anonymousToken,

                'submitted_at' =>
                    now(),
            ]);

            foreach ($questions as $question) {
                $answerData = $request->input(
                    "answers.{$question->id}",
                    []
                );

                FeedbackAnswer::create([
                    'feedback_response_id' =>
                        $response->id,

                    'feedback_question_id' =>
                        $question->id,

                    'rating_value' =>
                        $answerData['rating'] ?? null,

                    'text_answer' =>
                        $answerData['text'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route(
                'student.feedback.confirmation',
                $feedbackSession
            )
            ->with(
                'success',
                'Feedback submitted successfully and anonymously.'
            );

    }


    public function confirmation(FeedbackSession $feedbackSession)
    {
        $student = auth()->user();

        $submitted = FeedbackEligibility::where(
            'feedback_session_id',
            $feedbackSession->id
        )
            ->where('student_id', $student->id)
            ->where('has_submitted', true)
            ->exists();

        if (!$submitted) {
            return redirect()->route('student.feedback.index');
        }

        return view(
            'student.feedback.confirmation',
            compact('feedbackSession')
        );
    }
}