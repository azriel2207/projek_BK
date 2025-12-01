<?php

namespace App\Notifications;

use App\Models\EmailVerificationCode;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendVerificationCodeEmail extends Notification
{

    protected $verificationCode;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmailVerificationCode $verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * Get the notification's delivery channels.
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
        $expiresAt = $this->verificationCode->expires_at->format('H:i (d M Y)');

        return (new MailMessage)
            ->subject('Kode Verifikasi Email - Sistem BK Sekolah')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Terima kasih telah mendaftar di Sistem BK Sekolah.')
            ->line('Silakan gunakan kode verifikasi di bawah ini untuk memverifikasi email Anda:')
            ->line('')
            ->line('**' . $this->verificationCode->code . '**')
            ->line('')
            ->line('Kode ini akan berlaku hingga pukul ' . $expiresAt)
            ->line('Kode ini hanya berlaku untuk 15 menit.')
            ->line('')
            ->line('Jika Anda tidak membuat akun ini, abaikan email ini.')
            ->salutation('Salam hormat, Tim Sistem BK Sekolah');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'code' => $this->verificationCode->code,
        ];
    }
}
