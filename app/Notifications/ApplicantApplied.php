<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Application;

class ApplicantApplied extends Notification implements ShouldQueue
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
            ->subject('Pendaftar Baru: ' . $this->application->jobPosting->title)
            ->greeting('Halo ' . $notifiable->name)
            ->line('Ada pelamar baru untuk posisi ' . $this->application->jobPosting->title . '.')
            ->line('Nama Pelamar: ' . $this->application->studentProfile->user->name)
            ->action('Lihat Daftar Pelamar', route('company.applicant', ['id' => $this->application->jobPosting->id]));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'text' => 'Pelamar baru (' . $this->application->studentProfile->user->name . ') mendaftar di posisi ' . $this->application->jobPosting->title,
            'route' => 'company.applicant',
            'routeParam' => ['id' => $this->application->jobPosting->id]
        ];
    }
}
