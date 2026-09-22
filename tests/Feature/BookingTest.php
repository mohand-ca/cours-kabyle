<?php

namespace Tests\Feature;

use App\Livewire\Learner\Dashboard;
use App\Livewire\Learner\TeacherCatalog;
use App\Models\AvailabilitySlot;
use App\Models\Learner;
use App\Models\LessonSession;
use App\Models\Purchase;
use App\Models\TeacherProfile;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingTest extends TestCase
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

    public function test_learner_can_book_an_available_slot(): void
    {
        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();
        Purchase::factory()->create(['user_id' => $user->id, 'sessions_remaining' => 1]);

        Livewire::actingAs($user)
            ->test(TeacherCatalog::class)
            ->call('toggleSlots', $profile->id)
            ->call('selectSlot', $slot->id)
            ->set('selectedLearnerId', $learner->id)
            ->call('book')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('lesson_sessions', [
            'availability_slot_id' => $slot->id,
            'learner_id' => $learner->id,
            'teacher_profile_id' => $profile->id,
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('availability_slots', [
            'id' => $slot->id,
            'status' => 'booked',
        ]);
    }

    public function test_booking_requires_a_learner(): void
    {
        $user = $this->learnerUser();
        [$profile, $slot] = $this->approvedTeacherWithSlot();

        Livewire::actingAs($user)
            ->test(TeacherCatalog::class)
            ->call('toggleSlots', $profile->id)
            ->call('selectSlot', $slot->id)
            ->call('book')
            ->assertHasErrors(['selectedLearnerId']);

        $this->assertDatabaseMissing('lesson_sessions', ['availability_slot_id' => $slot->id]);
    }

    public function test_cannot_book_an_already_booked_slot(): void
    {
        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();
        Purchase::factory()->create(['user_id' => $user->id, 'sessions_remaining' => 1]);

        $slot->update(['status' => 'booked']);

        Livewire::actingAs($user)
            ->test(TeacherCatalog::class)
            ->call('toggleSlots', $profile->id)
            ->call('selectSlot', $slot->id)
            ->set('selectedLearnerId', $learner->id)
            ->call('book')
            ->assertHasErrors(['selectedSlotId']);

        $this->assertDatabaseMissing('lesson_sessions', ['availability_slot_id' => $slot->id]);
    }

    public function test_cannot_book_slot_for_another_users_learner(): void
    {
        $user = $this->learnerUser();
        $otherUser = $this->learnerUser();
        $otherLearner = $otherUser->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();

        Livewire::actingAs($user)
            ->test(TeacherCatalog::class)
            ->call('toggleSlots', $profile->id)
            ->call('selectSlot', $slot->id)
            ->set('selectedLearnerId', $otherLearner->id)
            ->call('book');

        $this->assertDatabaseMissing('lesson_sessions', ['availability_slot_id' => $slot->id]);
    }

    public function test_cancelling_a_session_releases_the_slot(): void
    {
        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();

        $slot->update(['status' => 'booked']);
        $session = LessonSession::factory()->create([
            'availability_slot_id' => $slot->id,
            'learner_id' => $learner->id,
            'teacher_profile_id' => $profile->id,
            'status' => 'confirmed',
        ]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->call('confirmCancel', $session->id)
            ->call('cancelSession')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('lesson_sessions', ['id' => $session->id, 'status' => 'cancelled']);
        $this->assertDatabaseHas('availability_slots', ['id' => $slot->id, 'status' => 'available']);
    }

    public function test_cannot_cancel_another_users_session(): void
    {
        $user = $this->learnerUser();
        $otherUser = $this->learnerUser();
        $otherLearner = $otherUser->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();

        $slot->update(['status' => 'booked']);
        $session = LessonSession::factory()->create([
            'availability_slot_id' => $slot->id,
            'learner_id' => $otherLearner->id,
            'teacher_profile_id' => $profile->id,
            'status' => 'confirmed',
        ]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->call('confirmCancel', $session->id)
            ->call('cancelSession');

        $this->assertDatabaseHas('lesson_sessions', ['id' => $session->id, 'status' => 'confirmed']);
    }

    public function test_dashboard_shows_upcoming_sessions(): void
    {
        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();

        $slot->update(['status' => 'booked']);
        LessonSession::factory()->create([
            'availability_slot_id' => $slot->id,
            'learner_id' => $learner->id,
            'teacher_profile_id' => $profile->id,
            'status' => 'confirmed',
        ]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertSee($profile->user->name);
    }

    public function test_cancelled_sessions_are_not_shown_on_dashboard(): void
    {
        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();

        $slot->update(['status' => 'available']);
        LessonSession::factory()->cancelled()->create([
            'availability_slot_id' => $slot->id,
            'learner_id' => $learner->id,
            'teacher_profile_id' => $profile->id,
        ]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertViewHas('upcomingSessions', fn ($s) => $s->count() === 0);
    }
}
