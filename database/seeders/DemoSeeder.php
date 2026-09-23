<?php

namespace Database\Seeders;

use App\Models\AvailabilitySlot;
use App\Models\Learner;
use App\Models\LessonSession;
use App\Models\Purchase;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = $this->createTeachers();
        $learners = $this->createLearners();
        $this->createBookings($teachers, $learners);
    }

    /** @return array<int, TeacherProfile> */
    private function createTeachers(): array
    {
        $teacherData = [
            [
                'name' => 'Amina Oukaci',
                'email' => 'amina.oukaci@demo.com',
                'timezone' => 'Africa/Algiers',
                'bio' => "Enseignante de kabyle depuis 12 ans, je donne des cours aux débutants et aux niveaux intermédiaires. Passionnée par la transmission de la langue et de la culture amazighe, j'adapte chaque cours au rythme de l'apprenant. J'ai accompagné plus de 80 élèves de la diaspora, aussi bien des adultes que des enfants.",
                'levels' => ['beginner', 'intermediate'],
                'languages' => ['kabyle', 'french'],
                'status' => 'approved',
            ],
            [
                'name' => 'Ferhat Aït-Ali',
                'email' => 'ferhat.aitali@demo.com',
                'timezone' => 'Africa/Algiers',
                'bio' => "Professeur certifié en langue et culture amazighes, diplômé de l'université de Tizi-Ouzou. Je propose des cours pour tous niveaux, du débutant absolu au niveau avancé. Ma méthode s'appuie sur des textes littéraires et des chansons kabyles pour rendre l'apprentissage vivant et ancré dans la culture.",
                'levels' => ['beginner', 'intermediate', 'advanced'],
                'languages' => ['kabyle', 'french', 'arabic'],
                'status' => 'approved',
            ],
            [
                'name' => 'Taziri Melloul',
                'email' => 'taziri.melloul@demo.com',
                'timezone' => 'Africa/Algiers',
                'bio' => "Spécialisée dans l'enseignement du kabyle aux enfants de la diaspora, je propose des cours ludiques et interactifs pour les 6-14 ans. Maman de trois enfants bilingues, je comprends les défis de transmettre la langue loin du pays. Mes cours mêlent comptines, histoires et jeux de rôle.",
                'levels' => ['beginner'],
                'languages' => ['kabyle', 'french'],
                'status' => 'approved',
            ],
            [
                'name' => 'Massinissa Idir',
                'email' => 'massinissa.idir@demo.com',
                'timezone' => 'Africa/Algiers',
                'bio' => "Linguiste de formation, je m'intéresse particulièrement à la grammaire et à l'écriture tifinagh. Mes cours avancés s'adressent à des apprenants ayant déjà des bases solides et souhaitant approfondir leur maîtrise de la langue écrite et parlée. Je donne aussi des ateliers de poésie amazighe.",
                'levels' => ['intermediate', 'advanced'],
                'languages' => ['kabyle', 'french', 'english'],
                'status' => 'approved',
            ],
            [
                'name' => 'Lynda Ath-Mansour',
                'email' => 'lynda.athmansour@demo.com',
                'timezone' => 'Africa/Algiers',
                'bio' => 'Enseignante motivée, je prépare actuellement ma candidature pour rejoindre la plateforme. Je donne des cours de kabyle depuis 3 ans dans mon quartier à Béjaïa et souhaite étendre mon enseignement à la diaspora.',
                'levels' => ['beginner', 'intermediate'],
                'languages' => ['kabyle', 'french'],
                'status' => 'pending',
            ],
        ];

        $profiles = [];

        foreach ($teacherData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                    'timezone' => $data['timezone'],
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('teacher');

            $profile = TeacherProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => $data['bio'],
                    'levels' => $data['levels'],
                    'languages' => $data['languages'],
                    'meet_link' => 'https://meet.google.com/'.substr(md5($data['email']), 0, 3).'-'.substr(md5($data['email']), 3, 4).'-'.substr(md5($data['email']), 7, 3),
                    'status' => $data['status'],
                    'submitted_at' => $data['status'] !== 'pending' ? now()->subDays(rand(3, 14)) : null,
                ]
            );

            if ($data['status'] === 'approved') {
                $this->createSlots($profile);
            }

            $profiles[] = $profile;
        }

        return array_filter($profiles, fn ($p) => $p->isApproved());
    }

    private function createSlots(TeacherProfile $profile): void
    {
        $occupiedSlots = [];

        // Créneaux passés (historique)
        foreach (range(1, 8) as $i) {
            $daysAgo = rand(3, 45);
            $hour = [9, 10, 11, 14, 15, 16, 17][array_rand([9, 10, 11, 14, 15, 16, 17])];
            $startsAt = Carbon::now()->subDays($daysAgo)->setHour($hour)->setMinute(0)->setSecond(0);
            $key = $startsAt->toDateTimeString();

            if (in_array($key, $occupiedSlots)) {
                continue;
            }
            $occupiedSlots[] = $key;

            AvailabilitySlot::create([
                'teacher_profile_id' => $profile->id,
                'starts_at' => $startsAt->utc(),
                'ends_at' => $startsAt->copy()->addHour()->utc(),
                'status' => 'booked',
            ]);
        }

        // Créneaux futurs disponibles
        foreach (range(1, 10) as $i) {
            $daysAhead = rand(1, 30);
            $hour = [9, 10, 11, 14, 15, 16, 17][array_rand([9, 10, 11, 14, 15, 16, 17])];
            $startsAt = Carbon::now()->addDays($daysAhead)->setHour($hour)->setMinute(0)->setSecond(0);
            $key = $startsAt->toDateTimeString();

            if (in_array($key, $occupiedSlots)) {
                continue;
            }
            $occupiedSlots[] = $key;

            AvailabilitySlot::create([
                'teacher_profile_id' => $profile->id,
                'starts_at' => $startsAt->utc(),
                'ends_at' => $startsAt->copy()->addHour()->utc(),
                'status' => 'available',
            ]);
        }
    }

    /**
     * @return array<int, array{user: User, learners: Collection}>
     */
    private function createLearners(): array
    {
        $diasporaNames = [
            ['Karim', 'Meziane'], ['Lydia', 'Bouzid'], ['Yanis', 'Ath-Saïd'], ['Nadia', 'Cheikh'],
            ['Sofiane', 'Hamidi'], ['Meriem', 'Aït-Kaci'], ['Rayan', 'Ouali'], ['Imane', 'Djoudi'],
            ['Adam', 'Belkacem'], ['Sara', 'Mohand'], ['Ines', 'Tahar'], ['Bilal', 'Ouamar'],
            ['Leila', 'Zerrouk'], ['Samy', 'Boudiaf'], ['Assia', 'Chabane'], ['Mehdi', 'Ould-Saïd'],
            ['Camille', 'Benamara'], ['Thomas', 'Aïssou'], ['Emma', 'Ferkal'], ['Lucas', 'Benali'],
            ['Julie', 'Ighil'], ['Pierre', 'Ouksel'], ['Marie', 'Tizi'], ['Antoine', 'Ameziane'],
            ['Céline', 'Khelifati'], ['Marc', 'Benbouzid'], ['Isabelle', 'Rahmani'], ['David', 'Berkane'],
            ['Fatima', 'Guessas'], ['Omar', 'Toudert'], ['Djamila', 'Lounis'], ['Réda', 'Achour'],
            ['Kahina', 'Aït-Hamouda'], ['Juba', 'Mabrouk'], ['Tinhinan', 'Oussalem'], ['Ania', 'Ferhat'],
            ['Massi', 'Challal'], ['Lila', 'Boumaza'], ['Taos', 'Mouloud'], ['Sonia', 'Larbi'],
            ['Kevin', 'Mezali'], ['Jennifer', 'Belhadj'], ['Nicolas', 'Ould-Braham'], ['Stéphanie', 'Oukil'],
            ['Alex', 'Dahmani'], ['Laura', 'Ighilahriz'], ['Julien', 'Makhloufi'], ['Charlotte', 'Achab'],
            ['Maxime', 'Amrouche'], ['Aurélie', 'Bensalem'],
        ];

        $accounts = [];

        foreach ($diasporaNames as $i => [$firstName, $lastName]) {
            $email = strtolower(str_replace([' ', "'", '-'], ['.', '', ''], $firstName.'.'.str_replace("'", '', $lastName))).'@demo.com';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $firstName.' '.$lastName,
                    'password' => Hash::make('password'),
                    'role' => 'learner',
                    'email_verified_at' => now()->subDays(rand(1, 60)),
                ]
            );
            $user->assignRole('learner');

            // Apprenant principal (self)
            $selfLearner = Learner::firstOrCreate(
                ['user_id' => $user->id, 'relationship' => 'self'],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'relationship' => 'self',
                ]
            );

            $userLearners = collect([$selfLearner]);

            // 30% des comptes ont un deuxième apprenant (enfant)
            if ($i % 3 === 0) {
                $childFirstNames = ['Anis', 'Rania', 'Idir', 'Tiziri', 'Mouloud', 'Lyna', 'Dihia', 'Jugurtha'];
                $childLearner = Learner::firstOrCreate(
                    ['user_id' => $user->id, 'relationship' => 'child'],
                    [
                        'first_name' => $childFirstNames[array_rand($childFirstNames)],
                        'last_name' => $lastName,
                        'relationship' => 'child',
                        'date_of_birth' => Carbon::now()->subYears(rand(6, 14))->format('Y-m-d'),
                    ]
                );
                $userLearners->push($childLearner);
            }

            $accounts[] = ['user' => $user, 'learners' => $userLearners];
        }

        return $accounts;
    }

    /**
     * @param  array<int, TeacherProfile>  $teachers
     * @param  array<int, array{user: User, learners: Collection}>  $learnerAccounts
     */
    private function createBookings(array $teachers, array $learnerAccounts): void
    {
        $packageKeys = ['starter', 'standard', 'premium'];

        // 35 des 50 comptes ont un achat
        $buyerAccounts = array_slice($learnerAccounts, 0, 35);

        foreach ($buyerAccounts as $account) {
            $packageKey = $packageKeys[array_rand($packageKeys)];
            $sessionsTotal = config('packages.'.$packageKey.'.sessions_count');
            $sessionsUsed = rand(0, min(4, $sessionsTotal));

            $purchase = Purchase::firstOrCreate(
                ['user_id' => $account['user']->id, 'stripe_session_id' => 'cs_demo_'.substr(md5($account['user']->email), 0, 20)],
                [
                    'package_key' => $packageKey,
                    'sessions_total' => $sessionsTotal,
                    'sessions_remaining' => $sessionsTotal - $sessionsUsed,
                    'status' => 'completed',
                ]
            );

            // Créer des réservations pour les sessions utilisées
            if ($sessionsUsed > 0) {
                $learner = $account['learners']->random();
                $usedSlots = AvailabilitySlot::whereIn('teacher_profile_id', collect($teachers)->pluck('id'))
                    ->where('status', 'booked')
                    ->whereDoesntHave('lessonSession')
                    ->limit($sessionsUsed)
                    ->get();

                foreach ($usedSlots as $slot) {
                    LessonSession::firstOrCreate(
                        ['availability_slot_id' => $slot->id],
                        [
                            'learner_id' => $learner->id,
                            'teacher_profile_id' => $slot->teacher_profile_id,
                            'purchase_id' => $purchase->id,
                            'status' => 'confirmed',
                        ]
                    );
                }
            }
        }
    }
}
