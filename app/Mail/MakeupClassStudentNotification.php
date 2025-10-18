<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\MakeUpClassRequest;

class MakeupClassStudentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $makeupRequest;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct(MakeUpClassRequest $makeupRequest, string $email)
    {
        $this->makeupRequest = $makeupRequest;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Makeup Class Scheduled - ' . $this->makeupRequest->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.makeup-class-student-notification',
            with: [
                'makeupRequest' => $this->makeupRequest,
                'email' => $this->email,
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
