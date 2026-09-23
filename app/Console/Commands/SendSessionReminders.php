<?php

namespace App\Console\Commands;

use App\Mail\SessionReminderMail;
use App\Models\LessonSession;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('sessions:send-reminders')]
#[Description('Send reminder emails for sessions starting in approximately 24 hours')]
class SendSessionReminders extends Command
{
    public function handle(): void
    {
        $sessions = LessonSession::where('status', 'confirmed')
            ->whereNull('reminder_sent_at')
            ->whereHas('availabilitySlot', function ($q) {
                $q->whereBetween('starts_at', [now()->addHours(23), now()->addHours(25)]);
            })
            ->with(['availabilitySlot', 'teacherProfile.user', 'learner.user'])
            ->get();

        foreach ($sessions as $session) {
            foreach ($session->learner->notificationAddresses() as $address) {
                Mail::to($address)->queue(new SessionReminderMail($session));
            }

            $session->update(['reminder_sent_at' => now()]);
        }

        $this->info("Sent reminders for {$sessions->count()} session(s).");
    }
}
