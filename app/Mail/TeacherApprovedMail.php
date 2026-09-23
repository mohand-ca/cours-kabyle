<?php

namespace App\Mail;

use App\Models\TeacherProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeacherApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public TeacherProfile $profile) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('teacher.mail.approved_subject'));
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.teacher-approved');
    }
}
