<?php

namespace App\Services;

use App\Models\FeedbackEligibility;
use App\Models\FeedbackSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FeedbackEligibilityService
{
    public function checkEligibility(
        User $student,
        FeedbackSession $feedbackSession
    ): array {
        // Student login is mandatory.
        if (! $student->isStudent()) {
            return [
                'eligible' => false,
                'reason' => 'Only student accounts can submit feedback.',
            ];
        }

        // Server-side release/deadline validation.
        if (! $feedbackSession->isAcceptingResponses()) {
            $message = 'Feedback is not currently available.';

            if (
                $feedbackSession->deadline_at &&
                now()->greaterThan($feedbackSession->deadline_at)
            ) {
                $message = 'Feedback submission time has expired.';
            }

            return [
                'eligible' => false,
                'reason' => $message,
            ];
        }

        $classSession = $feedbackSession->classSession;

        if (! $classSession) {
            return [
                'eligible' => false,
                'reason' => 'The related class session was not found.',
            ];
        }

        // Check enrollment from database, never from form data.
        $isEnrolled = DB::table('course_enrollments')
            ->where('user_id', $student->id)
            ->where('class_section_id', $classSession->class_section_id)
            ->where('status', 'active')
            ->exists();

        if (! $isEnrolled) {
            return [
                'eligible' => false,
                'reason' => 'You are not enrolled in this course.',
            ];
        }

        // Prevent duplicate feedback.
        $eligibilityRecord = FeedbackEligibility::where(
            'feedback_session_id',
            $feedbackSession->id
        )
            ->where('student_id', $student->id)
            ->first();

        if ($eligibilityRecord && $eligibilityRecord->has_submitted) {
            return [
                'eligible' => false,
                'reason' => 'You have already submitted feedback for this session.',
            ];
        }

        return [
            'eligible' => true,
            'eligibility_record' => $eligibilityRecord,
        ];
    }

    /*
     * Retained temporarily because older Faculty code may call this method.
     * The new system does NOT pre-enable individual students.
     */
    public function createEligibilityRecords(
        FeedbackSession $feedbackSession
    ): void {
        // Intentionally empty.
    }
}
