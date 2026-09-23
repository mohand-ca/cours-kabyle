<?php

namespace Tests\Feature;

use App\Livewire\Learner\TeacherCatalog;
use App\Mail\BookingConfirmedMail;
use App\Mail\BookingConfirmedTeacherMail;
use App\Mail\SessionReminderMail;
use App\Mail\TeacherApprovedMail;
use App\Models\AvailabilitySlot;
use App\Models\Learner;
use App\Models\LessonSession;
use App\Models\Purchase;
use App\Models\TeacherProfile;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    private function learnerUser(): User
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRole('learner');
        Learner::factory()->self()->create(['user_id' => $user->id]);

        return $user;
    }

    private function approvedTeacherWithSlot(): array
    {
        $profile = TeacherProfile::factory()->approved()->create();
        $slot = AvailabilitySlot::factory()->create(['teacher_profile_id' => $profile->id]);

        return [$profile, $slot];
    }

    public function test_booking_sends_confirmation_to_learner(): void
    {
        Mail::fake();

        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();
        Purchase::factory()->create(['user_id' => $user->id, 'sessions_remaining' => 1]);

        Livewire::actingAs($user)
            ->test(TeacherCatalog::class)
            ->call('toggleSlots', $profile->id)
            ->call('selectSlot', $slot->id)
            ->set('selectedLearnerId', $learner->id)
            ->call('book');

        Mail::assertQueued(BookingConfirmedMail::class);
    }

    public function test_booking_sends_confirmation_to_teacher(): void
    {
        Mail::fake();

        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();
        Purchase::factory()->create(['user_id' => $user->id, 'sessions_remaining' => 1]);

        Livewire::actingAs($user)
            ->test(TeacherCatalog::class)
            ->call('toggleSlots', $profile->id)
            ->call('selectSlot', $slot->id)
            ->set('selectedLearnerId', $learner->id)
            ->call('book');

        Mail::assertQueued(BookingConfirmedTeacherMail::class);
    }

    public function test_send_reminders_command_sends_email(): void
    {
        Mail::fake();

        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        $profile = TeacherProfile::factory()->approved()->create();

        $slot = AvailabilitySlot::factory()->create([
            'teacher_profile_id' => $profile->id,
            'starts_at' => now()->addHours(24),
            'ends_at' => now()->addHours(25),
            'status' => 'booked',
        ]);

        LessonSession::factory()->create([
            'availability_slot_id' => $slot->id,
            'learner_id' => $learner->id,
            'teacher_profile_id' => $profile->id,
            'status' => 'confirmed',
            'reminder_sent_at' => null,
        ]);

        $this->artisan('sessions:send-reminders')->assertSuccessful();

        Mail::assertQueued(SessionReminderMail::class);
        $this->assertDatabaseMissing('lesson_sessions', [
            'status' => 'confirmed',
            'reminder_sent_at' => null,
        ]);
    }

    public function test_send_reminders_command_is_idempotent(): void
    {
        Mail::fake();

        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        $profile = TeacherProfile::factory()->approved()->create();

        $slot = AvailabilitySlot::factory()->create([
            'teacher_profile_id' => $profile->id,
            'starts_at' => now()->addHours(24),
            'ends_at' => now()->addHours(25),
            'status' => 'booked',
        ]);

        $session = LessonSession::factory()->create([
            'availability_slot_id' => $slot->id,
            'learner_id' => $learner->id,
            'teacher_profile_id' => $profile->id,
            'status' => 'confirmed',
            'reminder_sent_at' => null,
        ]);

        $this->artisan('sessions:send-reminders');

        $countAfterFirstRun = count(Mail::queued(SessionReminderMail::class));

        $this->artisan('sessions:send-reminders');

        $this->assertCount($countAfterFirstRun, Mail::queued(SessionReminderMail::class));
        $this->assertNotNull($session->fresh()->reminder_sent_at);
    }

    public function test_teacher_approved_mail_is_queued(): void
    {
        Mail::fake();

        $profile = TeacherProfile::factory()->approved()->create();
        $profile->load('user');

        Mail::to($profile->user->email)->queue(new TeacherApprovedMail($profile));

        Mail::assertQueued(TeacherApprovedMail::class);
    }
}
