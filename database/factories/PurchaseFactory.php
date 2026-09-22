<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\SessionPackage;
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
        $package = SessionPackage::factory()->create();

        return [
            'user_id' => User::factory(),
            'package_id' => $package->id,
            'stripe_session_id' => 'cs_test_'.fake()->lexify('??????????????????????????'),
            'sessions_total' => $package->sessions_count,
            'sessions_remaining' => $package->sessions_count,
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
