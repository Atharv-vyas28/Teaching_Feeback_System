<?php
/** app/Notifications/FeedbackReleasedNotification.php */

namespace App\Notifications;

use App\Models\FeedbackSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackReleasedNotification extends Notification
{
    use Queueable;

    protected $count;
    protected $session;

    public function __construct(int $count, FeedbackSession $session)
    {
        $this->count   = $count;
        $this->session = $session;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message'   => "{$this->count} feedback response(s) released for session {$this->session->id}.",
            'sessionId' => $this->session->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Feedback Released')
            ->line("{$this->count} feedback response(s) have been released for your session.")
            ->action('View Feedback', url(route('faculty.feedback.index')));
    }
}
?>
