<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\ClassSession;
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
        if (!$student->isStudent()) {
            return [
                'eligible' => false,
                'reason' => 'Only student accounts can submit feedback.',
            ];
        }

        if (!$feedbackSession->isAcceptingResponses()) {
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

        if (!$classSession) {
            return [
                'eligible' => false,
                'reason' => 'The related class session was not found.',
            ];
        }

        $isEnrolled = DB::table('course_enrollments')
            ->where('user_id', $student->id)
            ->where(
                'class_section_id',
                $classSession->class_section_id
            )
            ->where('status', 'active')
            ->exists();

        if (!$isEnrolled) {
            return [
                'eligible' => false,
                'reason' => 'You are not enrolled in this course.',
            ];
        }

        $eligibilityRecord = FeedbackEligibility::where(
            'feedback_session_id',
            $feedbackSession->id
        )
            ->where('student_id', $student->id)
            ->first();

        if (
            $eligibilityRecord &&
            $eligibilityRecord->has_submitted
        ) {
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

    public function calculateScoreEligibility(
        FeedbackSession $feedbackSession
    ): void {
        $classSession = $feedbackSession->classSession;

        if (!$classSession) {
            throw new \RuntimeException(
                'The related class session was not found.'
            );
        }

        $eligibilityRecords = FeedbackEligibility::where(
            'feedback_session_id',
            $feedbackSession->id
        )
            ->where('has_submitted', true)
            ->get();

        foreach ($eligibilityRecords as $eligibility) {
            $attendanceMarked = Attendance::where(
                'class_session_id',
                $classSession->id
            )
                ->where(
                    'student_id',
                    $eligibility->student_id
                )
                ->where(
                    'source',
                    'feedback_day'
                )
                ->exists();

            if (!$attendanceMarked) {
                $eligibility->update([
                    'included_in_score' => false,
                    'attendance_weight' => null,
                ]);

                continue;
            }

            $attendanceWeight = $this->calculateOverallAttendance(
                $eligibility->student_id,
                $classSession->class_section_id
            );

            $eligibility->update([
                'included_in_score' => true,
                'attendance_weight' => $attendanceWeight,
            ]);
        }
    }

    private function calculateOverallAttendance(
        int $studentId,
        int $sectionId
    ): float {
        $regularSessionIds = ClassSession::where(
            'class_section_id',
            $sectionId
        )
            ->where('status', 'completed')
            ->whereHas('attendanceRecords', function ($query) {
                $query->where('source', 'regular');
            })
            ->pluck('id');

        $totalClasses = $regularSessionIds->count();

        if ($totalClasses === 0) {
            return 0.0000;
        }

        $attendedClasses = Attendance::where(
            'student_id',
            $studentId
        )
            ->whereIn(
                'class_session_id',
                $regularSessionIds
            )
            ->where('source', 'regular')
            ->whereIn(
                'status',
                ['present', 'late']
            )
            ->count();

        return round(
            $attendedClasses / $totalClasses,
            4
        );

    }


    public function createEligibilityRecords(
        FeedbackSession $feedbackSession
    ): void {
        // Intentionally empty.
    }
}
