<?php

namespace Tests\Feature;

use App\Livewire\Teacher\AvailabilityCalendar;
use App\Models\AvailabilityPattern;
use App\Models\AvailabilitySlot;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\AvailabilitySlotGenerator;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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

    private function approvedTeacher(string $timezone = 'UTC'): User
    {
        $teacher = User::factory()->create(['email_verified_at' => now(), 'timezone' => $timezone]);
        $teacher->assignRole('teacher');
        TeacherProfile::factory()->approved()->create(['user_id' => $teacher->id]);

        return $teacher;
    }

    public function test_availability_page_loads(): void
    {
        $teacher = $this->approvedTeacher();

        $this->actingAs($teacher)
            ->get(route('teacher.availability'))
            ->assertOk()
            ->assertSee(__('teacher.availability.title'));
    }

    public function test_approved_teacher_can_add_one_off_slot(): void
    {
        $teacher = $this->approvedTeacher();

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('oneOff.date', now()->addDays(2)->format('Y-m-d'))
            ->set('oneOff.start', '14:00')
            ->set('oneOff.duration', 60)
            ->call('addOneOff')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('availability_slots', 1);
        $this->assertEquals('available', AvailabilitySlot::first()->status);
    }

    public function test_pending_teacher_cannot_add_slot(): void
    {
        $teacher = User::factory()->create(['email_verified_at' => now()]);
        $teacher->assignRole('teacher');
        TeacherProfile::factory()->create(['user_id' => $teacher->id, 'status' => 'pending']);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->call('openAdd', 'one')
            ->assertSet('modal', null)
            ->set('oneOff.date', now()->addDays(2)->format('Y-m-d'))
            ->set('oneOff.start', '14:00')
            ->call('addOneOff');

        $this->assertDatabaseCount('availability_slots', 0);
    }

    public function test_overlapping_slot_is_rejected(): void
    {
        $teacher = $this->approvedTeacher();
        $start = now()->addDays(3)->setTime(14, 0);

        AvailabilitySlot::factory()->create([
            'teacher_profile_id' => $teacher->teacherProfile->id,
            'starts_at' => $start->copy(),
            'ends_at' => $start->copy()->addHour(),
            'status' => 'available',
        ]);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('oneOff.date', $start->format('Y-m-d'))
            ->set('oneOff.start', '14:30')
            ->set('oneOff.duration', 60)
            ->call('addOneOff')
            ->assertHasErrors('oneOff.start');

        $this->assertDatabaseCount('availability_slots', 1);
    }

    public function test_slot_within_24h_is_rejected(): void
    {
        $teacher = $this->approvedTeacher();

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('oneOff.date', now()->format('Y-m-d'))
            ->set('oneOff.start', now()->addHours(2)->format('H:i'))
            ->set('oneOff.duration', 60)
            ->call('addOneOff')
            ->assertHasErrors('oneOff.start');

        $this->assertDatabaseCount('availability_slots', 0);
    }

    public function test_one_off_slot_is_stored_as_utc(): void
    {
        $teacher = $this->approvedTeacher('America/Toronto');

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('oneOff.date', now()->addDays(2)->format('Y-m-d'))
            ->set('oneOff.start', '14:00')
            ->set('oneOff.duration', 60)
            ->call('addOneOff')
            ->assertHasNoErrors();

        // Toronto is UTC-4/UTC-5, so the stored UTC hour must differ from local 14:00.
        $this->assertNotEquals(14, (int) AvailabilitySlot::first()->starts_at->utc()->format('H'));
    }

    public function test_teacher_can_delete_available_slot(): void
    {
        $teacher = $this->approvedTeacher();
        $slot = AvailabilitySlot::factory()->create(['teacher_profile_id' => $teacher->teacherProfile->id]);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->call('askDelete', $slot->id)
            ->call('deleteSlot');

        $this->assertEquals('cancelled', $slot->fresh()->status);
    }

    public function test_booked_slot_cannot_be_deleted(): void
    {
        $teacher = $this->approvedTeacher();
        $slot = AvailabilitySlot::factory()->booked()->create(['teacher_profile_id' => $teacher->teacherProfile->id]);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->call('askDelete', $slot->id)
            ->call('deleteSlot');

        $this->assertEquals('booked', $slot->fresh()->status);
    }

    public function test_publishing_recurrence_creates_pattern_and_slots(): void
    {
        $teacher = $this->approvedTeacher();
        $dow = now()->addDay()->dayOfWeekIso - 1;

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('recDays', [$dow => [['18:00', '21:00']]])
            ->set('recDuration', 60)
            ->set('recBuffer', 0)
            ->set('recHorizon', 4)
            ->call('publishRecurrence');

        $this->assertDatabaseCount('availability_patterns', 1);
        // 18:00–21:00 at 60 min = 3 slots per occurrence, several occurrences over 4 weeks.
        $this->assertGreaterThanOrEqual(3, AvailabilitySlot::where('status', 'available')->count());
    }

    public function test_blocking_a_period_cancels_open_slots_but_keeps_booked(): void
    {
        $teacher = $this->approvedTeacher();
        $profileId = $teacher->teacherProfile->id;
        $day = now()->addDays(5)->setTime(10, 0);

        $open = AvailabilitySlot::factory()->create([
            'teacher_profile_id' => $profileId,
            'starts_at' => $day->copy(),
            'ends_at' => $day->copy()->addHour(),
            'status' => 'available',
        ]);
        $booked = AvailabilitySlot::factory()->booked()->create([
            'teacher_profile_id' => $profileId,
            'starts_at' => $day->copy()->addHours(2),
            'ends_at' => $day->copy()->addHours(3),
        ]);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('timeOff.from', now()->addDays(4)->format('Y-m-d'))
            ->set('timeOff.to', now()->addDays(6)->format('Y-m-d'))
            ->set('timeOff.reason', 'Holidays')
            ->call('addTimeOff');

        $this->assertDatabaseHas('time_offs', ['teacher_profile_id' => $profileId]);
        $this->assertEquals('cancelled', $open->fresh()->status);
        $this->assertEquals('booked', $booked->fresh()->status);
    }

    public function test_month_view_renders(): void
    {
        $teacher = $this->approvedTeacher();
        AvailabilitySlot::factory()->create(['teacher_profile_id' => $teacher->teacherProfile->id]);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->call('setView', 'month')
            ->assertSet('view', 'month')
            ->assertSee(__('teacher.availability.days_short.0'));
    }

    public function test_agenda_view_filters_to_booked_only(): void
    {
        $teacher = $this->approvedTeacher();
        AvailabilitySlot::factory()->create([
            'teacher_profile_id' => $teacher->teacherProfile->id,
            'starts_at' => now()->addDays(3)->setTime(14, 0),
            'ends_at' => now()->addDays(3)->setTime(15, 0),
            'status' => 'available',
        ]);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->call('setView', 'agenda')
            ->assertSet('view', 'agenda')
            ->assertDontSee(__('teacher.availability.agenda_empty_title'))
            ->call('setAgendaFilter', 'booked')
            ->assertSet('agendaFilter', 'booked')
            ->assertSee(__('teacher.availability.agenda_empty_title'));
    }

    public function test_copy_to_days_duplicates_ranges(): void
    {
        $teacher = $this->approvedTeacher();

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->set('recDays', [0 => [['18:00', '21:00']]])
            ->call('startCopy', 0)
            ->call('toggleCopyTarget', 2)
            ->call('applyCopy')
            ->assertSet('recDays', [0 => [['18:00', '21:00']], 2 => [['18:00', '21:00']]]);
    }

    public function test_jump_to_day_switches_to_week(): void
    {
        $teacher = $this->approvedTeacher();
        $date = now()->addDays(3);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->call('setView', 'month')
            ->call('jumpToDay', $date->toDateString())
            ->assertSet('view', 'week')
            ->assertSet('weekStart', $date->copy()->startOfWeek(Carbon::MONDAY)->toDateString());
    }

    public function test_deleting_a_series_stops_future_open_slots_but_keeps_booked(): void
    {
        $teacher = $this->approvedTeacher();
        $profile = $teacher->teacherProfile;
        $dow = now()->addDay()->dayOfWeekIso - 1;

        $pattern = AvailabilityPattern::factory()->create([
            'teacher_profile_id' => $profile->id,
            'day_of_week' => $dow,
            'start_time' => '18:00:00',
            'end_time' => '20:00:00',
            'slot_duration' => 60,
            'buffer' => 0,
            'starts_on' => now()->toDateString(),
        ]);
        app(AvailabilitySlotGenerator::class)->generate($profile);

        $slot = $profile->availabilitySlots()->where('status', 'available')->orderBy('starts_at')->first();
        $laterBooked = $profile->availabilitySlots()->where('starts_at', '>', $slot->starts_at)->orderBy('starts_at')->first();
        $laterBooked->update(['status' => 'booked']);

        Livewire::actingAs($teacher)
            ->test(AvailabilityCalendar::class)
            ->call('askDelete', $slot->id)
            ->set('delMode', 'series')
            ->call('deleteSlot');

        $this->assertNotNull($pattern->fresh()->until);
        $this->assertEquals('cancelled', $slot->fresh()->status);
        $this->assertEquals('booked', $laterBooked->fresh()->status);
    }
}
