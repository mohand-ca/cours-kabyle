<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'package_key' => 'starter',
            'stripe_session_id' => 'cs_test_'.fake()->lexify('??????????????????????????'),
            'sessions_total' => 5,
            'sessions_remaining' => 5,
            'status' => 'completed',
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending', 'stripe_session_id' => null]);
    }

    public function exhausted(): static
    {
        return $this->state(['sessions_remaining' => 0]);
    }
}
