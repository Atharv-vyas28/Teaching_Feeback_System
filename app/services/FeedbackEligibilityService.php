<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\FeedbackSession;
use App\Models\FeedbackEligibility;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FeedbackEligibilityService
{
    /**
     * Determine if a student (by authenticated user) can submit feedback.
     * NEVER trust any student-supplied IDs.
     */
    public function checkEligibility(User $student, FeedbackSession $feedbackSession): array
    {
        // 1. Must be a student
        if (!$student->isStudent()) {
            return ['eligible' => false, 'reason' => 'Not a student account.'];
        }

        // 2. Feedback session must be active
        if (!$feedbackSession->isActive()) {
            return ['eligible' => false, 'reason' => 'Feedback session is not active.'];
        }

        $classSession = $feedbackSession->classSession;

        // 3. Student must be enrolled in the course (from DB, not request)
        $isEnrolled = DB::table('course_enrollments')
            ->where('user_id', $student->id)
            ->where('class_section_id', $classSession->class_section_id)
            ->where('status', 'active')
            ->exists();

        if (!$isEnrolled) {
            return ['eligible' => false, 'reason' => 'You are not enrolled in this course.'];
        }

        // 4. Student must have been present
        $attendance = Attendance::where('class_session_id', $classSession->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$attendance || !$attendance->isPresent()) {
            return ['eligible' => false, 'reason' => 'You were not present in this class.'];
        }

        // 5. Feedback must be enabled for this student
        if (!$attendance->feedback_enabled) {
            return ['eligible' => false, 'reason' => 'Feedback is not enabled for you in this session.'];
        }

        // 6. Must not have already submitted
        $eligibilityRecord = FeedbackEligibility::where('feedback_session_id', $feedbackSession->id)
            ->where('student_id', $student->id)
            ->first();

        if ($eligibilityRecord && $eligibilityRecord->has_submitted) {
            return ['eligible' => false, 'reason' => 'You have already submitted feedback for this session.'];
        }

        return [
            'eligible'           => true,
            'attendance'         => $attendance,
            'eligibility_record' => $eligibilityRecord,
        ];
    }

    /**
     * Create eligibility records for all present+enabled students when feedback session is opened.
     */
    public function createEligibilityRecords(FeedbackSession $feedbackSession): void
    {
        $classSession = $feedbackSession->classSession;

        $presentAndEnabled = Attendance::where('class_session_id', $classSession->id)
            ->whereIn('status', ['present', 'late'])
            ->where('feedback_enabled', true)
            ->pluck('student_id');

        foreach ($presentAndEnabled as $studentId) {
            FeedbackEligibility::firstOrCreate(
                [
                    'feedback_session_id' => $feedbackSession->id,
                    'student_id'          => $studentId,
                ],
                ['has_submitted' => false]
            );
        }
    }
}