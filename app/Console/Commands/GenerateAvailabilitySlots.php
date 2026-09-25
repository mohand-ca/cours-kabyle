<?php

namespace App\Console\Commands;

use App\Models\TeacherProfile;
use App\Services\AvailabilitySlotGenerator;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('availability:generate')]
#[Description('Roll the booking horizon forward: materialise availability slots from approved teachers\' recurring patterns.')]
class GenerateAvailabilitySlots extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(AvailabilitySlotGenerator $generator): int
    {
        $total = 0;

        TeacherProfile::query()
            ->where('status', 'approved')
            ->with('user')
            ->each(function (TeacherProfile $teacher) use ($generator, &$total): void {
                $total += $generator->generate($teacher);
            });

        $this->info("Generated {$total} availability slot(s).");

        return self::SUCCESS;
    }
}
