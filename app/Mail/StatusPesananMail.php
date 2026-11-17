<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Pesanan;

class StatusPesananMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pesanan $pesanan;

    public function __construct(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update Status Pesanan #' . $this->pesanan->kode_pesanan,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.status_pesanan',
        );
    }
}