<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\FacultyCourse;
use App\Models\ClassSection;

class FacultyAssignedNotification extends Notification
{
    use Queueable;

    public $facultyCourse;

    /**
     * Create a new notification instance.
     */
    public function __construct(FacultyCourse $facultyCourse)
    {
        $this->facultyCourse = $facultyCourse;
        // Eager load relations for the email
        $this->facultyCourse->loadMissing(['section.course', 'semester', 'user']);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $courseName = $this->facultyCourse->section->course->name;
        $courseCode = $this->facultyCourse->section->course->code;
        $sectionName = $this->facultyCourse->section->name;
        $semesterName = $this->facultyCourse->semester->name;

        return (new MailMessage)
            ->subject("Course Assignment: $courseCode - $courseName")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been assigned as the faculty for the following course section:")
            ->line("**Course:** $courseCode - $courseName")
            ->line("**Section:** $sectionName")
            ->line("**Semester:** $semesterName")
            ->line("Please log in to your dashboard to view the class roster, schedule details, and manage feedback sessions.")
            ->action('View Dashboard', route('dashboard'))
            ->line('Thank you for your dedication to our students!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'faculty_course_id' => $this->facultyCourse->id,
            'class_section_id'  => $this->facultyCourse->class_section_id,
        ];
    }
}
