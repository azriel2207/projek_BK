<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmailNotification extends VerifyEmailNotification
{
    /**
     * Build the mail message.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Verifikasi Email - Sistem BK Sekolah')
            ->line('Terima kasih telah mendaftar di Sistem BK Sekolah!')
            ->line('Silakan klik tombol di bawah untuk memverifikasi email Anda:')
            ->action('Verifikasi Email', $url)
            ->line('Link verifikasi ini berlaku selama 60 menit.')
            ->line('Jika Anda tidak membuat akun ini, abaikan email ini.')
            ->salutation('Salam hormat, Tim Sistem BK Sekolah');
    }
}
