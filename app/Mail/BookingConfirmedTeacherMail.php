<?php

namespace App\Mail;

use App\Models\LessonSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmedTeacherMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public LessonSession $session) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('teacher.mail.booking_confirmed_subject'));
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.booking-confirmed-teacher');
    }
}
