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
            ->whereHas(
                'classSession',
                fn ($query) => $query->whereIn(
                    'class_section_id',
                    $enrolledSectionIds
                )
            )
            ->get();

        $available = [];
        $completed = [];
        $ineligible = [];

        foreach ($feedbackSessions as $session) {
            $eligibility = $this->eligibilityService->checkEligibility(
                $student,
                $session
            );

            $eligibilityRecord = FeedbackEligibility::where(
                'feedback_session_id',
                $session->id
            )
                ->where('student_id', $student->id)
                ->first();

            if ($eligibilityRecord && $eligibilityRecord->has_submitted) {
                $completed[] = $session;
            } elseif ($eligibility['eligible']) {
                $available[] = $session;
            } else {
                $hasAttendance = Attendance::where(
                    'class_session_id',
                    $session->class_session_id
                )
                    ->where('student_id', $student->id)
                    ->exists();

                if ($hasAttendance) {
                    $ineligible[] = [
                        'session' => $session,
                        'reason' => $eligibility['reason'],
                    ];
                }
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

        // Re-check eligibility on the server to prevent URL bypass.
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
            $questions,
            $eligibility
        ) {
            /*
             * Anonymous response:
             * No student_id is stored in feedback_responses.
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
             * Eligibility prevents duplicate submission.
             * It is deliberately not linked to the anonymous response.
             */
            $eligibilityRecord = $eligibility['eligibility_record'];

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
        });

        /*
         * Do NOT calculate faculty ratings here.
         * Ratings are calculated only when the admin releases feedback.
         */

        return redirect()
            ->route(
                'student.feedback.confirmation',
                $feedbackSession
            )
            ->with(
                'success',
                'Feedback submitted anonymously. Thank you!'
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