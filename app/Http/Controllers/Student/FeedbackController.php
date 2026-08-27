<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FeedbackSession;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackResponse;
use App\Models\FeedbackAnswer;
use App\Models\FeedbackEligibility;
use App\Services\FeedbackEligibilityService;
use App\Services\FeedbackRatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeedbackController extends Controller
{
    public function __construct(
        private FeedbackEligibilityService $eligibilityService,
        private FeedbackRatingService $ratingService
    ) {}

    public function index()
    {
        // Use authenticated user — NEVER trust any student-supplied ID
        $student = auth()->user();

        // Get sections student is enrolled in
        $enrolledSectionIds = DB::table('course_enrollments')
            ->where('user_id', $student->id)
            ->where('status', 'active')
            ->pluck('class_section_id');

        // Get all feedback sessions for those sections
        $feedbackSessions = FeedbackSession::with([
                'classSession.section.course',
                'classSession.section.faculty',
            ])
            ->whereHas('classSession', fn($q) => $q->whereIn('class_section_id', $enrolledSectionIds))
            ->get();

        // For each, check eligibility
        $available  = [];
        $completed  = [];
        $ineligible = [];

        foreach ($feedbackSessions as $session) {
            $eligibility = $this->eligibilityService->checkEligibility($student, $session);
            $eligibilityRecord = FeedbackEligibility::where('feedback_session_id', $session->id)
                ->where('student_id', $student->id)
                ->first();

            if ($eligibilityRecord && $eligibilityRecord->has_submitted) {
                $completed[] = $session;
            } elseif ($eligibility['eligible']) {
                $available[] = $session;
            } else {
                // Only show ineligible if they have an attendance record (they were in the class)
                $hasAttendance = \App\Models\Attendance::where('class_session_id', $session->class_session_id)
                    ->where('student_id', $student->id)->exists();
                if ($hasAttendance) {
                    $ineligible[] = ['session' => $session, 'reason' => $eligibility['reason']];
                }
            }
        }

        return view('student.feedback.index', compact('available', 'completed', 'ineligible'));
    }

    public function show(FeedbackSession $feedbackSession)
    {
        $student = auth()->user();

        // Server-side eligibility check — must pass all 6 criteria
        $eligibility = $this->eligibilityService->checkEligibility($student, $feedbackSession);

        if (!$eligibility['eligible']) {
            return redirect()->route('student.feedback.index')
                ->with('error', $eligibility['reason']);
        }

        $questions = FeedbackQuestion::active()->ordered()->get();
        $classSession = $feedbackSession->classSession;
        $faculty = $classSession->section->faculty->first();

        return view('student.feedback.show', compact('feedbackSession', 'questions', 'classSession', 'faculty'));
    }

    public function submit(Request $request, FeedbackSession $feedbackSession)
    {
        $student = auth()->user();

        // Re-check eligibility server-side on submit — defend against any bypass
        $eligibility = $this->eligibilityService->checkEligibility($student, $feedbackSession);

        if (!$eligibility['eligible']) {
            return redirect()->route('student.feedback.index')
                ->with('error', $eligibility['reason']);
        }

        $questions = FeedbackQuestion::active()->ordered()->get();

        // Validate answers
        $rules = ['answers' => 'required|array'];
        foreach ($questions as $q) {
            if ($q->type === 'rating') {
                $rules["answers.{$q->id}.rating"] = $q->is_required ? 'required|numeric|min:1|max:5' : 'nullable|numeric|min:1|max:5';
            } elseif ($q->type === 'text') {
                $rules["answers.{$q->id}.text"] = $q->is_required ? 'required|string|max:1000' : 'nullable|string|max:1000';
            }
        }
        $request->validate($rules);

        DB::transaction(function () use ($request, $feedbackSession, $student, $questions, $eligibility) {
            // Create anonymous response — NO student_id stored here
            $response = FeedbackResponse::create([
                'feedback_session_id' => $feedbackSession->id,
                'anonymous_token'     => Str::random(64),
                'submitted_at'        => now(),
            ]);

            // Store answers
            foreach ($questions as $question) {
                $answerData = $request->input("answers.{$question->id}", []);
                FeedbackAnswer::create([
                    'feedback_response_id'  => $response->id,
                    'feedback_question_id'  => $question->id,
                    'rating_value'          => $answerData['rating'] ?? null,
                    'text_answer'           => $answerData['text'] ?? null,
                ]);
            }

            // Mark student as having submitted (eligibility table only — no link to answer content)
            $eligibilityRecord = $eligibility['eligibility_record'];
            if ($eligibilityRecord) {
                $eligibilityRecord->update(['has_submitted' => true, 'submitted_at' => now()]);
            } else {
                FeedbackEligibility::create([
                    'feedback_session_id' => $feedbackSession->id,
                    'student_id'          => $student->id,
                    'has_submitted'       => true,
                    'submitted_at'        => now(),
                ]);
            }
        });

        return redirect()->route('student.feedback.confirmation', $feedbackSession)
            ->with('success', 'Feedback submitted anonymously. Thank you!');
    }

    public function confirmation(FeedbackSession $feedbackSession)
    {
        $student = auth()->user();
        $submitted = FeedbackEligibility::where('feedback_session_id', $feedbackSession->id)
            ->where('student_id', $student->id)
            ->where('has_submitted', true)
            ->exists();

        if (!$submitted) {
            return redirect()->route('student.feedback.index');
        }

        return view('student.feedback.confirmation', compact('feedbackSession'));
    }
}
