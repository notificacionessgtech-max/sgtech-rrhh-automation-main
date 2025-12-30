<?php

namespace App\Mail;

use App\Models\InvitationLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invitationLink;
    public $signedURL;

    public function __construct(InvitationLink $invitationLink, $signedURL)
    {
        $this->invitationLink = $invitationLink;
        $this->signedURL = $signedURL;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenid@ a SGTech',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'url' => $this->signedURL,
                'email' => $this->invitationLink->email
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
