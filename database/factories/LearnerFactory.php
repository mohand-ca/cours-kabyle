<?php

namespace Database\Factories;

use App\Models\Learner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Learner>
 */
class LearnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'date_of_birth' => fake()->optional(0.7)->dateTimeBetween('-50 years', '-5 years')?->format('Y-m-d'),
            'relationship' => fake()->randomElement(['self', 'child', 'spouse', 'other']),
            'notification_email' => fake()->optional(0.3)->safeEmail(),
            'points' => 0,
        ];
    }

    public function self(): static
    {
        return $this->state(['relationship' => 'self']);
    }

    public function child(): static
    {
        return $this->state(['relationship' => 'child']);
    }
}
