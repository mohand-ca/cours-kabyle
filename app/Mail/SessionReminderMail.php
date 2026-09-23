<?php

namespace App\Mail;

use App\Models\LessonSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SessionReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public LessonSession $session) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('learner.mail.reminder_subject', [
                'time' => $this->session->availabilitySlot->starts_at->format('H:i'),
            ])
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.session-reminder');
    }
}
