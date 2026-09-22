<?php

namespace Database\Factories;

use App\Models\AvailabilitySlot;
use App\Models\TeacherProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<AvailabilitySlot>
 */
class AvailabilitySlotFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = Carbon::instance(fake()->dateTimeBetween('+1 day', '+30 days'))
            ->setMinutes(0)->setSeconds(0);

        return [
            'teacher_profile_id' => TeacherProfile::factory(),
            'availability_pattern_id' => null,
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHour(),
            'status' => 'available',
        ];
    }

    public function booked(): static
    {
        return $this->state(['status' => 'booked']);
    }

    public function cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }
}
