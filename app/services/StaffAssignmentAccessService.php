<?php

namespace App\Services;

use App\Models\ClassSection;
use App\Models\FeedbackSession;
use App\Models\StaffCourse;
use App\Models\User;

class StaffAssignmentAccessService
{
    public function canManageSection(User $staff, ClassSection $section): bool
    {
        return $staff->isStaff()
            && $staff->is_active
            && StaffCourse::active()
                ->where('user_id', $staff->id)
                ->where('class_section_id', $section->id)
                ->exists();
    }

    public function canManageFeedback(User $staff, FeedbackSession $feedbackSession): bool
    {
        return $staff->isStaff()
            && $staff->is_active
            && StaffCourse::active()
                ->where('user_id', $staff->id)
                ->where('class_section_id', $feedbackSession->classSession->class_section_id)
                ->where('feedback_session_id', $feedbackSession->id)
                ->exists();
    }

    public function feedbackSessionsFor(User $staff)
    {
        return FeedbackSession::query()
            ->whereHas('staffAssignments', fn ($query) => $query->active()->where('user_id', $staff->id));
    }
}
