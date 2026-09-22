<?php

namespace Tests\Feature;

use App\Livewire\Teacher\AvailabilityCalendar;
use App\Models\AvailabilitySlot;
use App\Models\TeacherProfile;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AvailabilitySlotTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    private function approvedTeacher(): User
    {
        $teacher = User::factory()->create(['email_verified_at' => now()]);
        $teacher->assignRole('teacher');
        TeacherProfile::factory()->approved()->create(['user_id' => $teacher->id]);

        return $teacher;
    }

    public function test_approved_teacher_can_add_slot(): void
    {
        $teacher = $this->approvedTeacher();

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('date', now()->addDay()->format('Y-m-d'))
            ->set('startTime', '14:00')
            ->set('duration', 60)
            ->call('addSlot');

        $this->assertDatabaseCount('availability_slots', 1);

        $slot = AvailabilitySlot::first();
        $this->assertEquals('available', $slot->status);
    }

    public function test_pending_teacher_cannot_add_slot(): void
    {
        $teacher = User::factory()->create(['email_verified_at' => now()]);
        $teacher->assignRole('teacher');
        TeacherProfile::factory()->create(['user_id' => $teacher->id, 'status' => 'pending']);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('date', now()->addDay()->format('Y-m-d'))
            ->set('startTime', '14:00')
            ->set('duration', 60)
            ->call('addSlot')
            ->assertHasErrors(['date']);

        $this->assertDatabaseCount('availability_slots', 0);
    }

    public function test_overlapping_slot_is_rejected(): void
    {
        $teacher = $this->approvedTeacher();
        $profile = $teacher->teacherProfile;

        AvailabilitySlot::factory()->create([
            'teacher_profile_id' => $profile->id,
            'starts_at' => now()->addDay()->setHour(14)->setMinute(0)->setSecond(0),
            'ends_at' => now()->addDay()->setHour(15)->setMinute(0)->setSecond(0),
            'status' => 'available',
        ]);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('date', now()->addDay()->format('Y-m-d'))
            ->set('startTime', '14:30')
            ->set('duration', 60)
            ->call('addSlot')
            ->assertHasErrors(['startTime']);

        $this->assertDatabaseCount('availability_slots', 1);
    }

    public function test_teacher_can_cancel_available_slot(): void
    {
        $teacher = $this->approvedTeacher();
        $profile = $teacher->teacherProfile;

        $slot = AvailabilitySlot::factory()->create([
            'teacher_profile_id' => $profile->id,
        ]);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->call('cancelSlot', $slot->id);

        $this->assertEquals('cancelled', $slot->fresh()->status);
    }

    public function test_booked_slot_cannot_be_cancelled(): void
    {
        $teacher = $this->approvedTeacher();
        $profile = $teacher->teacherProfile;

        $slot = AvailabilitySlot::factory()->booked()->create([
            'teacher_profile_id' => $profile->id,
        ]);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->call('cancelSlot', $slot->id);

        $this->assertEquals('booked', $slot->fresh()->status);
    }

    public function test_slot_requires_future_date(): void
    {
        $teacher = $this->approvedTeacher();

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('date', now()->subDay()->format('Y-m-d'))
            ->set('startTime', '14:00')
            ->set('duration', 60)
            ->call('addSlot')
            ->assertHasErrors(['date']);
    }

    public function test_slot_stored_as_utc(): void
    {
        $teacher = User::factory()->create([
            'email_verified_at' => now(),
            'timezone' => 'America/Toronto',
        ]);
        $teacher->assignRole('teacher');
        TeacherProfile::factory()->approved()->create(['user_id' => $teacher->id]);

        $localDate = now()->addDays(2)->format('Y-m-d');

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('date', $localDate)
            ->set('startTime', '14:00')
            ->set('duration', 60)
            ->call('addSlot')
            ->assertHasNoErrors();

        $slot = AvailabilitySlot::first();
        $this->assertNotNull($slot);

        // Toronto is UTC-4 or UTC-5 — stored hour should differ from local 14:00
        $this->assertNotEquals(14, (int) $slot->starts_at->utc()->format('H'));
    }
}
