<?php

namespace App\Mail;

use App\Exports\DangerExport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class DangerMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $plant
    )
    {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $date = now()->format('d/m/Y');

        return new Envelope(
            subject:"{$this->plant} Danger Component Maintenance Follow Up — {$date}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.danger-mail',
            with: [
            'plant' => $this->plant,
            'date'  => now()->format('d/m/Y'), // ← ini yang kurang
        ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        //format nama file
        $fileName = str_replace(' ', '_', trim($this->plant)) . "_dangerlist_" . now()->format('d-m-Y') . ".xlsx";

        return [
            // generate excel lalu attach ke email
            Attachment::fromData(
                fn () => Excel::raw(new DangerExport($this->plant), ExcelFormat::XLSX),
                $fileName
            )->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}
