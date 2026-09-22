<?php

namespace Database\Factories;

use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeacherProfile>
 */
class TeacherProfileFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'teacher']),
            'bio' => fake()->paragraph(3),
            'levels' => fake()->randomElements(['beginner', 'intermediate', 'advanced'], fake()->numberBetween(1, 3)),
            'languages' => ['kabyle', fake()->randomElement(['french', 'english', 'arabic'])],
            'meet_link' => 'https://meet.google.com/'.fake()->lexify('???-????-???'),
            'status' => 'pending',
            'submitted_at' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(['status' => 'approved', 'submitted_at' => now()->subDay()]);
    }

    public function submitted(): static
    {
        return $this->state(['status' => 'pending', 'submitted_at' => now()->subHour()]);
    }

    public function suspended(): static
    {
        return $this->state(['status' => 'suspended', 'submitted_at' => now()->subDays(2)]);
    }
}
