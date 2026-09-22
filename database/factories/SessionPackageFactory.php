<?php

namespace Database\Factories;

use App\Models\SessionPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SessionPackage>
 */
class SessionPackageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sessionsCount = fake()->randomElement([5, 10, 20]);

        return [
            'name' => "Pack {$sessionsCount} séances",
            'sessions_count' => $sessionsCount,
            'price_cents' => $sessionsCount * 1000,
            'stripe_price_id' => 'price_test_'.fake()->lexify('??????????'),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
