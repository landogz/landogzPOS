<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public int $expiresInMinutes = 5,
        public string $email = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Landogz POS Login Code',
            from: config('mail.from.address', 'noreply@landogzpos.com'),
            replyTo: [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.login-otp',
        );
    }
}
