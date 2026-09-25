<?php

namespace App\Services;

use App\Models\AvailabilitySlot;
use App\Models\TeacherProfile;
use App\Models\TimeOff;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AvailabilitySlotGenerator
{
    /**
     * Materialise availability slots from a teacher's recurring patterns, up to
     * their booking horizon.
     *
     * Idempotent: never recreates a slot that already exists (in any status) at
     * the same start time, so booked and cancelled slots survive regeneration.
     * Days covered by a time-off and start times in the past are skipped.
     *
     * @return int Number of slots created.
     */
    public function generate(TeacherProfile $teacher): int
    {
        $teacher->loadMissing('user');
        $tz = $teacher->timezone();

        $today = Carbon::now($tz)->startOfDay();
        $horizonEnd = $today->copy()->addWeeks(max(1, (int) ($teacher->booking_horizon_weeks ?: 8)));
        $now = Carbon::now();

        $timeOffs = $teacher->timeOffs()->get();

        $existing = $teacher->availabilitySlots()
            ->where('starts_at', '>=', $today->copy()->utc())
            ->pluck('starts_at')
            ->map(fn (Carbon $date): string => $date->format('Y-m-d H:i'))
            ->flip();

        $rows = [];

        foreach ($teacher->availabilityPatterns()->where('is_active', true)->get() as $pattern) {
            // Work with date strings to stay in the teacher's timezone: `starts_on`/`until`
            // are date casts stored at UTC midnight, so comparing/iterating with the Carbon
            // instances directly would silently shift the day loop into UTC.
            $startDate = $today->toDateString();
            if ($pattern->starts_on && $pattern->starts_on->toDateString() > $startDate) {
                $startDate = $pattern->starts_on->toDateString();
            }

            $endDate = $horizonEnd->toDateString();
            if ($pattern->until && $pattern->until->toDateString() < $endDate) {
                $endDate = $pattern->until->toDateString();
            }

            $windowStart = $this->minutesOfDay($pattern->start_time);
            $windowEnd = $this->minutesOfDay($pattern->end_time);
            $step = $pattern->slot_duration + $pattern->buffer;

            if ($step <= 0) {
                continue;
            }

            for ($day = Carbon::parse($startDate, $tz)->startOfDay(); $day->toDateString() <= $endDate; $day->addDay()) {
                if (($day->dayOfWeekIso - 1) !== $pattern->day_of_week) {
                    continue;
                }

                if ($this->isTimeOff($timeOffs, $day)) {
                    continue;
                }

                for ($m = $windowStart; $m + $pattern->slot_duration <= $windowEnd; $m += $step) {
                    $startsAt = $day->copy()->setTime(intdiv($m, 60), $m % 60, 0)->utc();

                    if ($startsAt->lessThan($now)) {
                        continue;
                    }

                    $key = $startsAt->format('Y-m-d H:i');
                    if ($existing->has($key)) {
                        continue;
                    }
                    $existing->put($key, true);

                    $rows[] = [
                        'teacher_profile_id' => $teacher->id,
                        'availability_pattern_id' => $pattern->id,
                        'starts_at' => $startsAt,
                        'ends_at' => $startsAt->copy()->addMinutes($pattern->slot_duration),
                        'status' => 'available',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            AvailabilitySlot::insert($chunk);
        }

        return count($rows);
    }

    /**
     * @param  Collection<int, TimeOff>  $timeOffs
     */
    private function isTimeOff($timeOffs, Carbon $day): bool
    {
        $date = $day->toDateString();

        return $timeOffs->contains(
            fn ($off): bool => $date >= $off->starts_on->toDateString() && $date <= $off->ends_on->toDateString()
        );
    }

    private function minutesOfDay(string $time): int
    {
        [$hours, $minutes] = array_pad(explode(':', $time), 2, '0');

        return ((int) $hours) * 60 + (int) $minutes;
    }
}
