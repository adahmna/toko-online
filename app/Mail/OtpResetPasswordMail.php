<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $nama;

    public function __construct(string $otp, string $nama)
    {
        $this->otp  = $otp;
        $this->nama = $nama;
    }

    public function build()
    {
        return $this->subject('Kode OTP Reset Password - BSI Cyber Store')
            ->view('emails.otp-reset-password');
    }
}