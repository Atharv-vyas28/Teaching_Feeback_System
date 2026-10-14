<?php

namespace App\Notifications;

use App\Models\StaffCourse;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewStaffFeedbackAssignmentNotification extends Notification
{
    use Queueable;

    public function __construct(private StaffCourse $assignment)
    {
        $this->assignment->loadMissing(['section.course.department', 'section.semester', 'feedbackSession', 'assignedBy']);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $course = $this->assignment->section->course;

        return [
            'title' => 'New Feedback Assignment',
            'message' => "You have been assigned to collect feedback for {$course->name} ({$course->code}), Section {$this->assignment->section->section_name}.",
            'assignment_id' => $this->assignment->id,
            'feedback_session_id' => $this->assignment->feedback_session_id,
            'course_name' => $course->name,
            'course_code' => $course->code,
            'department' => $course->department?->name,
            'semester' => $this->assignment->section->semester?->name,
            'assigned_by' => $this->assignment->assignedBy?->name ?? 'Administrator',
            'assigned_at' => optional($this->assignment->assigned_at)->toDateTimeString(),
            'url' => route('staff.feedback.index'),
        ];
    }
}
