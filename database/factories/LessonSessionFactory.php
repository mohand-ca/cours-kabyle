<?php

namespace Database\Factories;

use App\Models\AvailabilitySlot;
use App\Models\Learner;
use App\Models\LessonSession;
use App\Models\TeacherProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LessonSession>
 */
class LessonSessionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $profile = TeacherProfile::factory()->approved()->create();
        $slot = AvailabilitySlot::factory()->create(['teacher_profile_id' => $profile->id]);

        return [
            'availability_slot_id' => $slot->id,
            'learner_id' => Learner::factory(),
            'teacher_profile_id' => $profile->id,
            'purchase_id' => null,
            'status' => 'confirmed',
        ];
    }

    public function cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }
}
