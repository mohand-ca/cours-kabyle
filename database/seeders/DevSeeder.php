<?php

namespace Database\Seeders;

use App\Models\AvailabilitySlot;
use App\Models\Learner;
use App\Models\Purchase;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DevSeeder extends Seeder
{
    public function run(): void
    {
        // Teacher de test — approuvé avec des créneaux
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@thamazight.com'],
            [
                'name' => 'Amina Oukaci',
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'email_verified_at' => now(),
            ]
        );
        $teacher->assignRole('teacher');

        if (! $teacher->teacherProfile) {
            $profile = TeacherProfile::create([
                'user_id' => $teacher->id,
                'bio' => 'Enseignante de kabyle depuis 10 ans, spécialisée dans les cours pour enfants et débutants.',
                'levels' => ['beginner', 'intermediate'],
                'languages' => ['kabyle', 'french'],
                'meet_link' => 'https://meet.google.com/abc-defg-hij',
                'status' => 'approved',
                'submitted_at' => now()->subDays(2),
            ]);

            foreach (range(1, 5) as $day) {
                $startsAt = Carbon::now()->addDays($day)->setHour(14)->setMinute(0)->setSecond(0);
                AvailabilitySlot::create([
                    'teacher_profile_id' => $profile->id,
                    'starts_at' => $startsAt,
                    'ends_at' => $startsAt->copy()->addHour(),
                    'status' => 'available',
                ]);
            }
        }

        // Learner de test — avec un profil self + un achat de test
        $learner = User::firstOrCreate(
            ['email' => 'learner@thamazight.com'],
            [
                'name' => 'Karim Meziane',
                'password' => bcrypt('password'),
                'role' => 'learner',
                'email_verified_at' => now(),
            ]
        );
        $learner->assignRole('learner');

        if ($learner->learners()->doesntExist()) {
            Learner::create([
                'user_id' => $learner->id,
                'first_name' => 'Karim',
                'last_name' => 'Meziane',
                'relationship' => 'self',
            ]);
        }

        if ($learner->purchases()->doesntExist()) {
            Purchase::create([
                'user_id' => $learner->id,
                'package_key' => 'starter',
                'stripe_session_id' => 'cs_test_dev_seed',
                'sessions_total' => 5,
                'sessions_remaining' => 5,
                'status' => 'completed',
            ]);
        }
    }
}
