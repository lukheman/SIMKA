<?php

namespace App\Mail;

use App\Models\Notifikasi;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NotifikasiAnggotaMail extends Mailable
{
    public function __construct(
        public Notifikasi $notifikasi,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notifikasi->judul . ' — Koperasi CU Mentari Kasih',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi-anggota',
            with: [
                'notifikasi' => $this->notifikasi,
                'anggota' => $this->notifikasi->anggota,
            ],
        );
    }
}
