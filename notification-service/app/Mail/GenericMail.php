<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectText,
        public string $bodyText,
        public ?string $attachmentPath = null,
        public ?string $attachmentName = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.generic-mail',
            with: [
                'bodyText' => $this->bodyText,
            ],
        );
    }

    public function attachments(): array
    {
        if ($this->attachmentPath && $this->attachmentName) {
            return [
                Attachment::fromPath($this->attachmentPath)
                    ->as($this->attachmentName)
            ];
        }

        return [];
    }
}
