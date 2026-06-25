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
        $status = $this->application->status->value;
        $posisi = $this->application->jobPosting->title;
        $perusahaan = $this->application->jobPosting->companyProfile->company_name ?? 'Perusahaan';

        $mail = (new MailMessage)
            ->subject('Update Status Lamaran: ' . $posisi)
            ->greeting('Halo ' . $notifiable->name);

        if ($status === 'Accepted') {
            $mail->line('Selamat! Anda telah DITERIMA untuk posisi ' . $posisi . ' di ' . $perusahaan . '.');
            $mail->line('Silakan tunggu informasi selanjutnya dari pihak perusahaan atau cek portal secara berkala.');
        } elseif ($status === 'Rejected') {
            $mail->line('Mohon maaf, lamaran Anda untuk posisi ' . $posisi . ' di ' . $perusahaan . ' DITOLAK.');
            $mail->line('Jangan patah semangat dan terus mencoba lowongan lainnya!');
        } elseif ($status === 'Reviewed') {
            $mail->line('Kabar baik! Lamaran Anda untuk posisi ' . $posisi . ' di ' . $perusahaan . ' saat ini sedang DI-REVIEW oleh perusahaan.');
            $mail->line('Perusahaan sedang mempertimbangkan profil dan CV Anda.');
        } else {
            // Fallback ke status lain
            $mail->line('Status lamaran Anda untuk posisi ' . $posisi . ' di ' . $perusahaan . ' telah diupdate menjadi: ' . $status);
        }

        return $mail->action('Cek Status Lamaran', route('student.application'));
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->application->status->value;
        $posisi = $this->application->jobPosting->title;
        $perusahaan = $this->application->jobPosting->companyProfile->company_name ?? 'Perusahaan';

        if ($status === 'Accepted') {
            $text = 'Selamat! Anda diterima untuk posisi ' . $posisi . ' di ' . $perusahaan;
        } elseif ($status === 'Rejected') {
            $text = 'Mohon maaf, Anda ditolak untuk posisi ' . $posisi . ' di ' . $perusahaan;
        } elseif ($status === 'Reviewed') {
            $text = 'Lamaran Anda sedang di-review untuk posisi ' . $posisi . ' di ' . $perusahaan;
        } else {
            $text = 'Status lamaran ' . $posisi . ' di ' . $perusahaan . ' menjadi ' . $status;
        }

        return [
            'text' => $text,
            'route' => 'student.application',
            'routeParam' => []
        ];
    }
}
