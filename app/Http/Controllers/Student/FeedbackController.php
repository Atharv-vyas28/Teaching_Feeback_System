<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
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

        if (! $eligibility['eligible']) {
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

        // Re-check every security condition on the server.
        $eligibility = $this->eligibilityService->checkEligibility(
            $student,
            $feedbackSession
        );

        if (! $eligibility['eligible']) {
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

        DB::transaction(function () use (
            $request,
            $feedbackSession,
            $student,
            $questions
        ) {
            /*
             * Lock this student's record during submission.
             * This prevents duplicate feedback from repeated clicks/requests.
             */
            $eligibilityRecord = FeedbackEligibility::where(
                'feedback_session_id',
                $feedbackSession->id
            )
                ->where('student_id', $student->id)
                ->lockForUpdate()
                ->first();

            if ($eligibilityRecord?->has_submitted) {
                abort(422, 'You have already submitted feedback for this session.');
            }

            /*
             * Anonymous response:
             * The response table does NOT contain student_id.
             */
            $response = FeedbackResponse::create([
                'feedback_session_id' => $feedbackSession->id,
                'anonymous_token' => Str::random(64),
                'submitted_at' => now(),
            ]);

            foreach ($questions as $question) {
                $answerData = $request->input(
                    "answers.{$question->id}",
                    []
                );

                FeedbackAnswer::create([
                    'feedback_response_id' => $response->id,
                    'feedback_question_id' => $question->id,
                    'rating_value' => $answerData['rating'] ?? null,
                    'text_answer' => $answerData['text'] ?? null,
                ]);
            }

            /*
             * This table only records whether the authenticated student submitted.
             * It is not connected to the anonymous feedback response.
             */
            if ($eligibilityRecord) {
                $eligibilityRecord->update([
                    'has_submitted' => true,
                    'submitted_at' => now(),
                ]);
            } else {
                FeedbackEligibility::create([
                    'feedback_session_id' => $feedbackSession->id,
                    'student_id' => $student->id,
                    'has_submitted' => true,
                    'submitted_at' => now(),
                ]);
            }

            /*
             * Automatic feedback-based attendance.
             *
             * If regular attendance already exists, it becomes Present.
             * If no attendance exists, a new Present record is created.
             */
            Attendance::updateOrCreate(
                [
                    'class_session_id' => $feedbackSession->class_session_id,
                    'student_id' => $student->id,
                ],
                [
                    'marked_by' => $feedbackSession->assigned_staff_id
                        ?? $feedbackSession->classSession->conducted_by,
                    'status' => 'present',
                    'source' => 'feedback',
                    'feedback_enabled' => false,
                    'marked_at' => now(),
                    'remarks' => 'Marked present after valid feedback submission.',
                ]
            );
        });

        return redirect()
            ->route(
                'student.feedback.confirmation',
                $feedbackSession
            )
            ->with(
                'success',
                'Feedback submitted anonymously. Your attendance has been marked present.'
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

        if (! $submitted) {
            return redirect()->route('student.feedback.index');
        }

        return view(
            'student.feedback.confirmation',
            compact('feedbackSession')
        );
    }
}