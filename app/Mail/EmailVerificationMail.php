<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationCode;

    public function __construct(User $user, $verificationCode)
    {
        $this->user = $user;
        $this->verificationCode = $verificationCode;
    }

    public function build()
    {
        return $this->subject('Verifikasi Email - Sistem BK Sekolah')
                    ->view('emails.verification')
                    ->with([
                        'name' => $this->user->username,
                        'verificationCode' => $this->verificationCode,
                    ]);
    }
}