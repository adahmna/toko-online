<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\LamaranKerja;

class LamaranDiterimaMail extends Mailable
{
    use Queueable, SerializesModels;

    public LamaranKerja $lamaran;

    public function __construct(LamaranKerja $lamaran)
    {
        $this->lamaran = $lamaran;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat! Lamaran Kerja Anda Diterima - BSI Cyber Store',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lamaran_diterima',
        );
    }
}