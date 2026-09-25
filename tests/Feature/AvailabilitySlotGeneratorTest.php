<?php

namespace Tests\Feature;

use App\Models\AvailabilityPattern;
use App\Models\AvailabilitySlot;
use App\Models\TeacherProfile;
use App\Models\TimeOff;
use App\Models\User;
use App\Services\AvailabilitySlotGenerator;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilitySlotGeneratorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function approvedProfile(string $timezone = 'UTC'): TeacherProfile
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'timezone' => $timezone]);
        $user->assignRole('teacher');

        return TeacherProfile::factory()->approved()->create(['user_id' => $user->id]);
    }

    private function weeklyPattern(TeacherProfile $profile, array $overrides = []): AvailabilityPattern
    {
        return AvailabilityPattern::factory()->create(array_merge([
            'teacher_profile_id' => $profile->id,
            'day_of_week' => now()->addDay()->dayOfWeekIso - 1,
            'start_time' => '18:00:00',
            'end_time' => '21:00:00',
            'slot_duration' => 60,
            'buffer' => 0,
            'starts_on' => now()->toDateString(),
            'until' => null,
        ], $overrides));
    }

    public function test_it_generates_slots_from_a_weekly_pattern(): void
    {
        $profile = $this->approvedProfile();
        $this->weeklyPattern($profile);

        $created = app(AvailabilitySlotGenerator::class)->generate($profile);

        $this->assertGreaterThanOrEqual(3, $created);
        $this->assertDatabaseCount('availability_slots', $created);
    }

    public function test_generated_slots_use_the_teacher_local_time(): void
    {
        // Regression: a pattern at 09:00 for an Algiers (UTC+1) teacher must land at
        // 09:00 Algiers (08:00 UTC), not shift by the teacher's offset.
        $profile = $this->approvedProfile('Africa/Algiers');
        $this->weeklyPattern($profile, ['start_time' => '09:00:00', 'end_time' => '10:00:00']);

        app(AvailabilitySlotGenerator::class)->generate($profile);

        $slot = $profile->availabilitySlots()->orderBy('starts_at')->first();
        $this->assertNotNull($slot);
        $this->assertSame('09:00', $slot->starts_at->copy()->setTimezone('Africa/Algiers')->format('H:i'));
        $this->assertSame('08:00', $slot->starts_at->utc()->format('H:i'));
    }

    public function test_generation_is_idempotent(): void
    {
        $profile = $this->approvedProfile();
        $this->weeklyPattern($profile);

        $generator = app(AvailabilitySlotGenerator::class);
        $first = $generator->generate($profile);
        $second = $generator->generate($profile);

        $this->assertGreaterThan(0, $first);
        $this->assertSame(0, $second);
        $this->assertDatabaseCount('availability_slots', $first);
    }

    public function test_it_respects_the_buffer_between_slots(): void
    {
        $profile = $this->approvedProfile();
        // 18:00–20:00 with 60 min duration + 30 min buffer -> only the 18:00 slot fits per day
        // (a 19:30 slot would run past 20:00), whereas without a buffer 19:00 would also fit.
        $this->weeklyPattern($profile, ['end_time' => '20:00:00', 'buffer' => 30]);

        $created = app(AvailabilitySlotGenerator::class)->generate($profile);

        $this->assertGreaterThan(0, $created);
        $this->assertTrue(
            AvailabilitySlot::all()->every(fn (AvailabilitySlot $slot) => $slot->starts_at->utc()->format('H:i') === '18:00'),
            'Buffer should prevent a second slot, so every generated slot must start at 18:00.'
        );
    }

    public function test_it_skips_days_covered_by_time_off(): void
    {
        $profile = $this->approvedProfile();
        $this->weeklyPattern($profile);

        TimeOff::factory()->create([
            'teacher_profile_id' => $profile->id,
            'starts_on' => now()->toDateString(),
            'ends_on' => now()->addWeeks(12)->toDateString(),
        ]);

        $created = app(AvailabilitySlotGenerator::class)->generate($profile);

        $this->assertSame(0, $created);
    }

    public function test_command_generates_for_approved_teachers(): void
    {
        $profile = $this->approvedProfile();
        $this->weeklyPattern($profile);

        $this->artisan('availability:generate')->assertSuccessful();

        $this->assertGreaterThan(0, AvailabilitySlot::count());
    }
}
