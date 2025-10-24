<?php

namespace App\Mail;

use App\Models\EhrRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EhrUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public EhrRecord $ehrRecord;

    /**
     * Create a new message instance.
     */
    public function __construct(EhrRecord $ehrRecord)
    {
        $this->ehrRecord = $ehrRecord;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Electronic Health Record Has Been Updated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ehr-update-notification',
            with: [
                'ehrRecord' => $this->ehrRecord,
                'patient' => $this->ehrRecord->patient,
                'appointment' => $this->ehrRecord->appointment,
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
        return [];
    }
}
