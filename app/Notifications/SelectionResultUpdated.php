<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Application;

class SelectionResultUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Application $application)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update Status Lamaran: ' . $this->application->jobPosting->title)
            ->greeting('Halo ' . $notifiable->name)
            ->line('Status lamaran Anda untuk posisi ' . $this->application->jobPosting->title . ' telah diupdate.')
            ->line('Status saat ini: ' . $this->application->status->value)
            ->action('Cek Status Lamaran', route('student.application'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'text' => 'Status lamaran Anda untuk ' . $this->application->jobPosting->title . ' telah menjadi ' . $this->application->status->value,
            'route' => 'student.application',
            'routeParam' => []
        ];
    }
}
