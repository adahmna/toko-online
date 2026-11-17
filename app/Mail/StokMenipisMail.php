<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StokMenipisMail extends Mailable
{
    use Queueable, SerializesModels;

    public $produkMenipis;

    public function __construct($produkMenipis)
    {
        $this->produkMenipis = $produkMenipis;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Peringatan Stok Produk Menipis - BSI Cyber Store',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.stok_menipis',
        );
    }
}