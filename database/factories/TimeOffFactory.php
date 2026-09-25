<?php

namespace Database\Factories;

use App\Models\TeacherProfile;
use App\Models\TimeOff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<TimeOff>
 */
class TimeOffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $from = Carbon::today()->addDays(fake()->numberBetween(2, 20));

        return [
            'teacher_profile_id' => TeacherProfile::factory(),
            'starts_on' => $from->toDateString(),
            'ends_on' => $from->copy()->addDays(fake()->numberBetween(0, 6))->toDateString(),
            'reason' => fake()->optional()->sentence(2),
        ];
    }
}
