<?php

namespace Database\Factories;

use App\Models\AvailabilityPattern;
use App\Models\TeacherProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AvailabilityPattern>
 */
class AvailabilityPatternFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->numberBetween(8, 18);

        return [
            'teacher_profile_id' => TeacherProfile::factory(),
            'day_of_week' => fake()->numberBetween(0, 6), // 0 = Monday … 6 = Sunday
            'start_time' => sprintf('%02d:00:00', $start),
            'end_time' => sprintf('%02d:00:00', $start + 2),
            'slot_duration' => 60,
            'buffer' => 0,
            'starts_on' => null,
            'until' => null,
            'is_active' => true,
        ];
    }
}
