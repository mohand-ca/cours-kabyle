<?php

namespace App\Livewire\Teacher;

use App\Livewire\Forms\OneOffSlotForm;
use App\Livewire\Forms\TimeOffForm;
use App\Models\AvailabilityPattern;
use App\Models\AvailabilitySlot;
use App\Models\TeacherProfile;
use App\Models\TimeOff;
use App\Services\AvailabilitySlotGenerator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AvailabilityCalendar extends Component
{
    /** Pixels per minute (56px per hour) — mirrors the design grid. */
    private const PX = 56 / 60;

    public string $view = 'week';

    public string $weekStart = '';

    /** First day of the displayed month (Y-m-01), used by the month view. */
    public string $monthCursor = '';

    /** Agenda status filter: all|open|booked. */
    public string $agendaFilter = 'all';

    /** Selected day index (0=Monday) for the narrow/mobile week view. */
    public int $mobileDay = 0;

    /** Recurrence "copy to other days" state. */
    public ?int $copyFrom = null;

    /** @var array<int, bool> */
    public array $copyTo = [];

    /** Learner timezone preview key (config/timezones.php), or null for the teacher's own timezone. */
    public ?string $previewTz = null;

    /** @var array{type:string,tab?:string,id?:int}|null */
    public ?array $modal = null;

    public string $delMode = 'one';

    // Global scheduling settings (mirror teacher_profiles).
    public int $defaultDuration = 60;

    public int $buffer = 0;

    public int $horizonWeeks = 8;

    public OneOffSlotForm $oneOff;

    public TimeOffForm $timeOff;

    // Recurrence builder state: [dayOfWeek => [[from, to], ...]].
    /** @var array<int, array<int, array{0:string,1:string}>> */
    public array $recDays = [];

    public int $recDuration = 60;

    public int $recBuffer = 0;

    public int $recHorizon = 8;

    public function mount(): void
    {
        $teacher = $this->teacher();
        $tz = $teacher ? $teacher->timezone() : config('app.timezone');

        $this->weekStart = Carbon::now($tz)->startOfWeek(Carbon::MONDAY)->toDateString();
        $this->monthCursor = Carbon::now($tz)->startOfMonth()->toDateString();
        $this->mobileDay = Carbon::now($tz)->dayOfWeekIso - 1;

        if ($teacher) {
            $this->defaultDuration = $teacher->default_slot_duration ?? 60;
            $this->buffer = $teacher->slot_buffer ?? 0;
            $this->horizonWeeks = $teacher->booking_horizon_weeks ?? 8;
        }

        $this->recDuration = $this->defaultDuration;
        $this->recBuffer = $this->buffer;
        $this->recHorizon = $this->horizonWeeks;
        $this->resetOneOff();
        $this->resetTimeOff();
    }

    // ---------------------------------------------------------------- Navigation & preview

    public function setView(string $view): void
    {
        if (in_array($view, ['week', 'month', 'agenda'], true)) {
            $this->view = $view;
        }
    }

    public function navPrev(): void
    {
        if ($this->view === 'month') {
            $this->monthCursor = Carbon::parse($this->monthCursor)->subMonthNoOverflow()->toDateString();
        } else {
            $this->weekStart = Carbon::parse($this->weekStart)->subWeek()->toDateString();
        }
    }

    public function navNext(): void
    {
        if ($this->view === 'month') {
            $this->monthCursor = Carbon::parse($this->monthCursor)->addMonthNoOverflow()->toDateString();
        } else {
            $this->weekStart = Carbon::parse($this->weekStart)->addWeek()->toDateString();
        }
    }

    public function goToday(): void
    {
        $tz = $this->teacherTz();
        $this->weekStart = Carbon::now($tz)->startOfWeek(Carbon::MONDAY)->toDateString();
        $this->monthCursor = Carbon::now($tz)->startOfMonth()->toDateString();
        $this->mobileDay = Carbon::now($tz)->dayOfWeekIso - 1;
    }

    public function goToDate(string $date): void
    {
        if ($date === '') {
            return;
        }
        $this->weekStart = Carbon::parse($date)->startOfWeek(Carbon::MONDAY)->toDateString();
    }

    /** Jump from the month view to the week containing a given day. */
    public function jumpToDay(string $date): void
    {
        $day = Carbon::parse($date);
        $this->weekStart = $day->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $this->mobileDay = $day->dayOfWeekIso - 1;
        $this->view = 'week';
    }

    public function setAgendaFilter(string $filter): void
    {
        if (in_array($filter, ['all', 'open', 'booked'], true)) {
            $this->agendaFilter = $filter;
        }
    }

    public function setMobileDay(int $index): void
    {
        $this->mobileDay = max(0, min(6, $index));
    }

    public function setPreviewTz(string $key): void
    {
        $this->previewTz = ($key === '' || $key === 'home') ? null : $key;
    }

    // ---------------------------------------------------------------- Settings

    public function setDefaultDuration(int $minutes): void
    {
        $this->defaultDuration = $minutes;
        $this->persistSettings();
    }

    public function setBuffer(int $minutes): void
    {
        $this->buffer = $minutes;
        $this->persistSettings();
    }

    public function setHorizon(int $weeks): void
    {
        $this->horizonWeeks = $weeks;
        $this->persistSettings();
        if ($teacher = $this->teacher()) {
            app(AvailabilitySlotGenerator::class)->generate($teacher);
        }
    }

    private function persistSettings(): void
    {
        $this->teacher()?->update([
            'default_slot_duration' => $this->defaultDuration,
            'slot_buffer' => $this->buffer,
            'booking_horizon_weeks' => $this->horizonWeeks,
        ]);
    }

    // ---------------------------------------------------------------- Modals

    public function openAdd(string $tab = 'one'): void
    {
        if (! $this->canEdit()) {
            $this->flashError(__('teacher.availability.not_approved'));

            return;
        }
        if ($tab === 'one') {
            $this->resetOneOff();
        } elseif (empty($this->recDays)) {
            $this->recDays = [0 => [['18:00', '21:00']]];
        }
        $this->modal = ['type' => 'add', 'tab' => $tab];
    }

    public function openLeave(): void
    {
        if (! $this->canEdit()) {
            $this->flashError(__('teacher.availability.not_approved'));

            return;
        }
        $this->resetTimeOff();
        $this->modal = ['type' => 'leave'];
    }

    public function openBlock(int $slotId): void
    {
        $slot = $this->teacher()?->availabilitySlots()->find($slotId);
        if (! $slot) {
            return;
        }
        if ($slot->isBooked()) {
            $this->modal = ['type' => 'booked', 'id' => $slotId];
        } elseif ($slot->starts_at->isPast()) {
            $this->flashError(__('teacher.availability.slot_past'));
        } elseif (! $this->canEdit()) {
            $this->flashError(__('teacher.availability.not_approved'));
        } else {
            $this->modal = ['type' => 'slot', 'id' => $slotId];
        }
    }

    public function askDelete(int $slotId): void
    {
        $this->delMode = 'one';
        $this->modal = ['type' => 'del', 'id' => $slotId];
    }

    public function closeModal(): void
    {
        $this->modal = null;
        $this->copyFrom = null;
    }

    public function copyMeet(): void
    {
        $this->flashSuccess(__('teacher.availability.toast_meet_copied'));
    }

    public function contactLearner(): void
    {
        $this->modal = null;
        $this->flashSuccess(__('teacher.availability.toast_contact_sent'));
    }

    public function supportCancel(): void
    {
        $this->modal = null;
        $this->flashSuccess(__('teacher.availability.toast_support_sent'));
    }

    // ---------------------------------------------------------------- One-off slot

    public function createFromClick(int $dayIndex, int $minute): void
    {
        if (! $this->canEdit()) {
            $this->flashError(__('teacher.availability.not_approved'));

            return;
        }

        $tz = $this->displayTz();
        $minute = max(0, min(1425, intdiv($minute, 15) * 15));
        $clicked = Carbon::parse($this->weekStart, $this->teacherTz())
            ->startOfWeek(Carbon::MONDAY)
            ->addDays($dayIndex)
            ->setTimezone($tz)
            ->setTime(intdiv($minute, 60), $minute % 60);

        $local = $clicked->copy()->setTimezone($this->teacherTz());

        if ($local->isPast()) {
            $this->flashError(__('teacher.availability.err_past'));

            return;
        }
        if ($local->lessThan(Carbon::now()->addDay())) {
            $this->flashError(__('teacher.availability.err_notice'));

            return;
        }
        if ($this->timeOffOn($local)) {
            $this->flashError(__('teacher.availability.err_leave'));

            return;
        }

        $this->oneOff->date = $local->toDateString();
        $this->oneOff->start = $local->format('H:i');
        $this->oneOff->duration = $this->defaultDuration;
        $this->modal = ['type' => 'add', 'tab' => 'one'];
    }

    public function addOneOff(): void
    {
        if (! $this->canEdit()) {
            return;
        }
        $this->oneOff->validate();

        $teacher = $this->teacher();
        $startsAt = Carbon::createFromFormat('Y-m-d H:i', "{$this->oneOff->date} {$this->oneOff->start}", $this->teacherTz());

        $error = $this->slotError($startsAt, $this->oneOff->duration);
        if ($error) {
            $this->addError('oneOff.start', $error);

            return;
        }

        AvailabilitySlot::create([
            'teacher_profile_id' => $teacher->id,
            'starts_at' => $startsAt->copy()->utc(),
            'ends_at' => $startsAt->copy()->addMinutes($this->oneOff->duration)->utc(),
            'status' => 'available',
        ]);

        $this->modal = null;
        $this->flashSuccess(__('teacher.availability.toast_slot_added'));
    }

    /** Returns a validation error key for a candidate slot, or null when it is valid. */
    private function slotError(Carbon $startsAt, int $duration): ?string
    {
        if ($startsAt->isPast()) {
            return __('teacher.availability.err_past');
        }
        if ($startsAt->lessThan(Carbon::now()->addDay())) {
            return __('teacher.availability.err_notice');
        }
        if ($this->timeOffOn($startsAt)) {
            return __('teacher.availability.err_leave');
        }

        $endsAt = $startsAt->copy()->addMinutes($duration);

        $overlap = $this->teacher()->availabilitySlots()
            ->whereIn('status', ['available', 'booked'])
            ->where('starts_at', '<', $endsAt->copy()->addMinutes($this->buffer)->utc())
            ->where('ends_at', '>', $startsAt->copy()->subMinutes($this->buffer)->utc())
            ->exists();

        return $overlap ? __('teacher.availability.err_overlap') : null;
    }

    // ---------------------------------------------------------------- Recurrence

    public function toggleRecDay(int $dow): void
    {
        if (isset($this->recDays[$dow])) {
            unset($this->recDays[$dow]);
        } else {
            $template = ! empty($this->recDays) ? reset($this->recDays) : [['18:00', '21:00']];
            $this->recDays[$dow] = $template;
        }
        ksort($this->recDays);
    }

    public function addRecRange(int $dow): void
    {
        $this->recDays[$dow][] = ['09:00', '12:00'];
    }

    public function removeRecRange(int $dow, int $index): void
    {
        unset($this->recDays[$dow][$index]);
        $this->recDays[$dow] = array_values($this->recDays[$dow]);
        if (empty($this->recDays[$dow])) {
            unset($this->recDays[$dow]);
        }
    }

    public function applyPreset(string $key): void
    {
        $presets = [
            'weeknights' => [[0, 1, 2, 3, 4], ['18:00', '21:00']],
            'mornings' => [[0, 2, 4], ['09:00', '12:00']],
            'weekend' => [[5, 6], ['10:00', '13:00']],
        ];
        if (! isset($presets[$key])) {
            return;
        }
        [$days, $range] = $presets[$key];
        $this->recDays = [];
        foreach ($days as $dow) {
            $this->recDays[$dow] = [[$range[0], $range[1]]];
        }
        $this->modal = ['type' => 'add', 'tab' => 'rec'];
    }

    public function setRecDuration(int $minutes): void
    {
        $this->recDuration = $minutes;
    }

    public function setRecBuffer(int $minutes): void
    {
        $this->recBuffer = $minutes;
    }

    public function setRecHorizon(int $weeks): void
    {
        $this->recHorizon = $weeks;
    }

    public function startCopy(int $dow): void
    {
        $this->copyFrom = $this->copyFrom === $dow ? null : $dow;
        $this->copyTo = [];
    }

    public function toggleCopyTarget(int $dow): void
    {
        $this->copyTo[$dow] = ! ($this->copyTo[$dow] ?? false);
    }

    public function applyCopy(): void
    {
        if ($this->copyFrom === null || ! isset($this->recDays[$this->copyFrom])) {
            return;
        }
        $source = $this->recDays[$this->copyFrom];
        foreach ($this->copyTo as $dow => $on) {
            if ($on) {
                $this->recDays[(int) $dow] = $source;
            }
        }
        ksort($this->recDays);
        $this->copyFrom = null;
        $this->copyTo = [];
    }

    public function askPublish(): void
    {
        if ($this->recurrenceErrors()['blocked']) {
            return;
        }
        $this->modal = ['type' => 'pub'];
    }

    public function backToRecurrence(): void
    {
        $this->modal = ['type' => 'add', 'tab' => 'rec'];
    }

    public function publishRecurrence(): void
    {
        if (! $this->canEdit() || $this->recurrenceErrors()['blocked']) {
            return;
        }

        $teacher = $this->teacher();
        $today = Carbon::now($this->teacherTz())->toDateString();

        foreach ($this->recDays as $dow => $ranges) {
            foreach ($ranges as $range) {
                AvailabilityPattern::create([
                    'teacher_profile_id' => $teacher->id,
                    'day_of_week' => $dow,
                    'start_time' => $range[0],
                    'end_time' => $range[1],
                    'slot_duration' => $this->recDuration,
                    'buffer' => $this->recBuffer,
                    'starts_on' => $today,
                    'is_active' => true,
                ]);
            }
        }

        $this->horizonWeeks = $this->recHorizon;
        $this->persistSettings();
        $created = app(AvailabilitySlotGenerator::class)->generate($teacher);

        $this->modal = null;
        $this->recDays = [];
        $this->flashSuccess(trans_choice('teacher.availability.toast_recurrence_published', $created, ['count' => $created]));
    }

    // ---------------------------------------------------------------- Time-off (leave)

    public function addTimeOff(): void
    {
        if (! $this->canEdit()) {
            return;
        }
        $this->timeOff->validate();

        $from = Carbon::parse($this->timeOff->from);
        $to = Carbon::parse($this->timeOff->to);
        if ($to->lessThan($from)) {
            $this->addError('timeOff.to', __('teacher.availability.err_leave_order'));

            return;
        }

        $teacher = $this->teacher();
        $cancelled = 0;

        DB::transaction(function () use ($teacher, $from, $to, &$cancelled) {
            TimeOff::create([
                'teacher_profile_id' => $teacher->id,
                'starts_on' => $from->toDateString(),
                'ends_on' => $to->toDateString(),
                'reason' => $this->timeOff->reason,
            ]);

            $cancelled = $teacher->availabilitySlots()
                ->where('status', 'available')
                ->whereBetween('starts_at', [
                    $from->copy()->startOfDay()->utc(),
                    $to->copy()->endOfDay()->utc(),
                ])
                ->update(['status' => 'cancelled']);
        });

        $this->modal = null;
        $this->flashSuccess(trans_choice('teacher.availability.toast_leave_added', $cancelled, ['count' => $cancelled]));
    }

    // ---------------------------------------------------------------- Deletion

    public function deleteSlot(): void
    {
        $slot = $this->teacher()?->availabilitySlots()->find($this->modal['id'] ?? null);
        if (! $slot) {
            $this->modal = null;

            return;
        }
        if ($slot->isBooked()) {
            $this->modal = null;
            $this->flashError(__('teacher.availability.err_delete_booked'));

            return;
        }

        if ($slot->availability_pattern_id && $this->delMode === 'series') {
            $pattern = $slot->availabilityPattern;
            $pattern?->update(['until' => $slot->starts_at->copy()->setTimezone($this->teacherTz())->subDay()->toDateString()]);

            $this->teacher()->availabilitySlots()
                ->where('availability_pattern_id', $slot->availability_pattern_id)
                ->where('status', 'available')
                ->where('starts_at', '>=', $slot->starts_at)
                ->update(['status' => 'cancelled']);

            $this->flashSuccess(__('teacher.availability.toast_series_deleted'));
        } else {
            $slot->update(['status' => 'cancelled']);
            $this->flashSuccess(__('teacher.availability.toast_slot_deleted'));
        }

        $this->modal = null;
    }

    // ---------------------------------------------------------------- Helpers

    private function teacher(): ?TeacherProfile
    {
        return Auth::user()->teacherProfile;
    }

    private function canEdit(): bool
    {
        return (bool) $this->teacher()?->isApproved();
    }

    private function teacherTz(): string
    {
        return $this->teacher()?->timezone() ?? config('app.timezone');
    }

    private function displayTz(): string
    {
        if ($this->previewTz && ($iana = config('timezones.preview.'.$this->previewTz))) {
            return $iana;
        }

        return $this->teacherTz();
    }

    private function timeOffOn(Carbon $moment): bool
    {
        $date = $moment->copy()->setTimezone($this->teacherTz())->toDateString();

        return $this->teacher()->timeOffs()
            ->where('starts_on', '<=', $date)
            ->where('ends_on', '>=', $date)
            ->exists();
    }

    private function resetOneOff(): void
    {
        $this->oneOff->date = Carbon::now($this->teacherTz())->addDays(2)->toDateString();
        $this->oneOff->start = '18:00';
        $this->oneOff->duration = $this->defaultDuration;
    }

    private function resetTimeOff(): void
    {
        $this->timeOff->from = Carbon::now($this->teacherTz())->addWeek()->toDateString();
        $this->timeOff->to = Carbon::now($this->teacherTz())->addWeek()->addDays(4)->toDateString();
        $this->timeOff->reason = null;
    }

    private function flashSuccess(string $message): void
    {
        session()->flash('toast', ['kind' => 'ok', 'message' => $message]);
    }

    private function flashError(string $message): void
    {
        session()->flash('toast', ['kind' => 'err', 'message' => $message]);
    }

    /**
     * @return array{blocked:bool, perWeek:int, total:int, summary:array<int,string>}
     */
    private function recurrenceErrors(): array
    {
        $perWeek = 0;
        $blocked = false;
        $summary = [];

        foreach ($this->recDays as $dow => $ranges) {
            foreach ($ranges as $i => $range) {
                $from = $this->minutes($range[0]);
                $to = $this->minutes($range[1]);
                if ($to <= $from) {
                    $blocked = true;

                    continue;
                }
                foreach ($ranges as $j => $other) {
                    if ($i !== $j && $from < $this->minutes($other[1]) && $to > $this->minutes($other[0])) {
                        $blocked = true;
                    }
                }
                $step = $this->recDuration + $this->recBuffer;
                $perWeek += $step > 0 ? max(0, intdiv($to - $from + $this->recBuffer, $step)) : 0;
                $summary[] = __('teacher.availability.days.'.$dow).' · '.$range[0].'–'.$range[1].' · '.$this->recDuration.' min';
            }
        }

        $total = $perWeek * $this->recHorizon;

        return [
            'blocked' => $blocked || $total === 0,
            'perWeek' => $perWeek,
            'total' => $total,
            'summary' => $summary,
        ];
    }

    private function minutes(string $time): int
    {
        [$h, $m] = array_pad(explode(':', $time), 2, '0');

        return ((int) $h) * 60 + (int) $m;
    }

    /** @return array{bg:string,bd:string,fg:string,dot:string} */
    private function statusColors(string $status): array
    {
        return match ($status) {
            'booked' => ['bg' => '#EEF2FF', 'bd' => '#C7D2FE', 'fg' => '#4338CA', 'dot' => '#6366F1'],
            'past' => ['bg' => '#F1ECE6', 'bd' => '#E2DBD3', 'fg' => '#78716C', 'dot' => '#A8A29E'],
            default => ['bg' => '#E8F3EC', 'bd' => '#C5E4CF', 'fg' => '#2F7D5B', 'dot' => '#3A9A6E'],
        };
    }

    public function render()
    {
        $teacher = $this->teacher();
        $displayTz = $this->displayTz();
        $now = Carbon::now();

        $weekStart = Carbon::parse($this->weekStart, $this->teacherTz())->startOfWeek(Carbon::MONDAY)->setTimezone($displayTz)->startOfDay();
        $weekEnd = $weekStart->copy()->addDays(7);

        $slots = collect();
        if ($teacher) {
            $slots = $teacher->availabilitySlots()
                ->whereIn('status', ['available', 'booked'])
                ->whereBetween('starts_at', [$weekStart->copy()->subDay()->utc(), $weekEnd->copy()->addDay()->utc()])
                ->with('lessonSession.learner')
                ->orderBy('starts_at')
                ->get();
        }

        $timeOffs = $teacher ? $teacher->timeOffs()->get() : collect();

        // Build 7 day columns.
        $columns = [];
        $statHours = 0;
        $statOpen = 0;
        $statBooked = 0;

        for ($i = 0; $i < 7; $i++) {
            $colDate = $weekStart->copy()->addDays($i);
            $colStart = $colDate->copy();
            $dateStr = $colDate->toDateString();

            $pastMin = max(0, min(1440, $colStart->diffInMinutes($now, false)));
            $noticeMin = max(0, min(1440, $colStart->diffInMinutes($now->copy()->addDay(), false)));

            $leave = $timeOffs->first(fn (TimeOff $off) => $dateStr >= $off->starts_on->toDateString() && $dateStr <= $off->ends_on->toDateString());

            $blocks = [];
            $colHasOpen = false;
            $colHasBooked = false;
            foreach ($slots as $slot) {
                $localStart = $slot->starts_at->copy()->setTimezone($displayTz);
                if ($localStart->toDateString() !== $dateStr) {
                    continue;
                }
                $duration = $slot->durationMinutes();
                $minute = $localStart->hour * 60 + $localStart->minute;

                if ($slot->isBooked()) {
                    $status = 'booked';
                } elseif ($slot->starts_at->isPast()) {
                    $status = 'past';
                } else {
                    $status = 'open';
                }
                $colors = $this->statusColors($status);
                $learnerName = $slot->lessonSession?->learner?->first_name.' '.$slot->lessonSession?->learner?->last_name;

                $blocks[] = [
                    'id' => $slot->id,
                    'top' => round($minute * self::PX) + 1,
                    'height' => max(24, round(min($duration, 1440 - $minute) * self::PX) - 2),
                    'colors' => $colors,
                    'time' => $localStart->format('H:i').' – '.$localStart->copy()->addMinutes($duration)->format('H:i'),
                    'label' => $status === 'booked' ? trim($learnerName) : ($status === 'past' ? __('teacher.availability.status_past') : __('teacher.availability.status_available')),
                    'rec' => (bool) $slot->availability_pattern_id,
                    'lock' => $status === 'booked',
                ];

                $colHasOpen = $colHasOpen || $status === 'open';
                $colHasBooked = $colHasBooked || $status === 'booked';

                // Stats for the visible week (teacher-local dates).
                if ($slot->starts_at->betweenIncluded($weekStart->copy()->utc(), $weekEnd->copy()->utc())) {
                    $statHours += $duration / 60;
                    if ($status === 'open') {
                        $statOpen++;
                    } elseif ($status === 'booked') {
                        $statBooked++;
                    }
                }
            }

            $columns[] = [
                'index' => $i,
                'dow' => __('teacher.availability.days_short.'.$i),
                'num' => $colDate->day,
                'isToday' => $dateStr === $now->copy()->setTimezone($displayTz)->toDateString(),
                'pastHeight' => $pastMin * self::PX,
                'noticeTop' => $pastMin * self::PX,
                'noticeHeight' => max(0, ($noticeMin - $pastMin) * self::PX),
                'showNotice' => ($noticeMin - $pastMin) > 90 && $noticeMin < 1440,
                'hasNow' => $pastMin > 0 && $pastMin < 1440,
                'nowTop' => $pastMin * self::PX - 1,
                'leaveReason' => $leave?->reason ?: ($leave ? __('teacher.availability.leave_default') : null),
                'hasLeave' => (bool) $leave,
                'hasOpen' => $colHasOpen,
                'hasBooked' => $colHasBooked,
                'blocks' => $blocks,
            ];
        }

        // Day chips for the narrow/mobile week view.
        $dayChips = [];
        foreach ($columns as $col) {
            $dots = [];
            if ($col['hasLeave']) {
                $dots[] = '#D69E2E';
            }
            if ($col['hasOpen']) {
                $dots[] = '#3A9A6E';
            }
            if ($col['hasBooked']) {
                $dots[] = '#6366F1';
            }
            $dayChips[] = [
                'index' => $col['index'],
                'dow' => $col['dow'],
                'num' => $col['num'],
                'isToday' => $col['isToday'],
                'active' => $col['index'] === $this->mobileDay,
                'dots' => $dots,
            ];
        }
        $mobileDayTitle = $this->weekStart !== ''
            ? Carbon::parse($this->weekStart)->addDays($this->mobileDay)->translatedFormat('l j F')
            : '';

        $hours = [];
        for ($h = 1; $h < 24; $h++) {
            $hours[] = ['top' => $h * 56, 'label' => sprintf('%02d:00', $h)];
        }

        $isEmpty = ! $teacher || (! $teacher->availabilityPatterns()->exists()
            && ! $teacher->availabilitySlots()->whereIn('status', ['available', 'booked'])->where('starts_at', '>', $now)->exists());

        $month = $this->view === 'month' ? $this->buildMonth($displayTz, $now, $timeOffs) : ['cells' => [], 'label' => '', 'head' => []];
        $agenda = $this->view === 'agenda' ? $this->buildAgenda($displayTz, $now, $timeOffs) : ['groups' => [], 'empty' => true];

        $rangeLabel = match ($this->view) {
            'month' => $month['label'],
            default => $this->weekRangeLabel($weekStart),
        };

        return view('livewire.teacher.availability.index', array_merge([
            'profile' => $teacher,
            'canEdit' => $this->canEdit(),
            'isEmpty' => $isEmpty,
            'columns' => $columns,
            'dayChips' => $dayChips,
            'mobileDayTitle' => $mobileDayTitle,
            'hours' => $hours,
            'monthCells' => $month['cells'],
            'monthHead' => $month['head'],
            'agendaGroups' => $agenda['groups'],
            'agendaEmpty' => $agenda['empty'],
            'showNav' => $this->view !== 'agenda',
            'weekRangeLabel' => $rangeLabel,
            'statHours' => rtrim(rtrim(number_format($statHours, 1, ',', ' '), '0'), ','),
            'statOpen' => $statOpen,
            'statBooked' => $statBooked,
            'tzActiveLabel' => $this->tzLabel($this->teacherTz()),
            'tzActiveIana' => $this->teacherTz(),
            'tzActiveGmt' => $this->gmt($this->teacherTz()),
            'tzOptions' => $this->tzOptions(),
            'previewLabel' => $this->previewTz ? $this->tzLabel($displayTz) : null,
            'previewGmt' => $this->previewTz ? $this->gmt($displayTz) : null,
            'previewDiff' => $this->previewTz ? $this->tzDiff($displayTz) : null,
            'horizonEnd' => Carbon::now($this->teacherTz())->addWeeks($this->horizonWeeks)->translatedFormat('j F Y'),
        ], $this->modalData($slots, $displayTz)))->layout('components.layouts.app');
    }

    // ---------------------------------------------------------------- Render helpers

    private function weekRangeLabel(Carbon $weekStart): string
    {
        $end = $weekStart->copy()->addDays(6);

        return $weekStart->translatedFormat('j M').' – '.$end->translatedFormat('j M Y');
    }

    /**
     * @param  Collection<int, TimeOff>  $timeOffs
     * @return array{cells:array<int,array<string,mixed>>, label:string, head:array<int,string>}
     */
    private function buildMonth(string $displayTz, Carbon $now, $timeOffs): array
    {
        $teacher = $this->teacher();
        $monthStart = Carbon::parse($this->monthCursor, $this->teacherTz())->startOfMonth();
        $gridStart = $monthStart->copy()->setTimezone($displayTz)->startOfWeek(Carbon::MONDAY)->startOfDay();
        $rows = (int) ceil(($monthStart->dayOfWeekIso - 1 + $monthStart->daysInMonth) / 7);
        $gridEnd = $gridStart->copy()->addDays($rows * 7);
        $todayStr = $now->copy()->setTimezone($displayTz)->toDateString();

        $slots = collect();
        if ($teacher) {
            $slots = $teacher->availabilitySlots()
                ->whereIn('status', ['available', 'booked'])
                ->whereBetween('starts_at', [$gridStart->copy()->utc(), $gridEnd->copy()->utc()])
                ->get();
        }

        $byDay = [];
        foreach ($slots as $slot) {
            $key = $slot->starts_at->copy()->setTimezone($displayTz)->toDateString();
            $byDay[$key][] = $slot->isBooked() ? 'booked' : ($slot->starts_at->isPast() ? 'past' : 'open');
        }

        $cells = [];
        for ($k = 0; $k < $rows * 7; $k++) {
            $date = $gridStart->copy()->addDays($k);
            $dateStr = $date->toDateString();
            $statuses = $byDay[$dateStr] ?? [];
            $open = count(array_filter($statuses, fn ($s) => $s === 'open'));
            $booked = count(array_filter($statuses, fn ($s) => $s === 'booked'));
            $past = count(array_filter($statuses, fn ($s) => $s === 'past'));
            $leave = $timeOffs->first(fn (TimeOff $off) => $dateStr >= $off->starts_on->toDateString() && $dateStr <= $off->ends_on->toDateString());

            $dots = [];
            if ($leave) {
                $dots[] = '#D69E2E';
            }
            for ($j = 0; $j < min($open, 3); $j++) {
                $dots[] = '#3A9A6E';
            }
            for ($j = 0; $j < min($booked, 2); $j++) {
                $dots[] = '#6366F1';
            }

            $cells[] = [
                'date' => $dateStr,
                'num' => $date->day,
                'inMonth' => $date->month === $monthStart->month,
                'isToday' => $dateStr === $todayStr,
                'isPast' => $date->lt($now->copy()->setTimezone($displayTz)->startOfDay()),
                'isLeave' => (bool) $leave,
                'open' => $open,
                'booked' => $booked,
                'past' => $past,
                'openLabel' => __('teacher.availability.count_open', ['count' => $open]),
                'bookedLabel' => trans_choice('teacher.availability.count_booked', $booked, ['count' => $booked]),
                'pastLabel' => trans_choice('teacher.availability.count_past', $past, ['count' => $past]),
                'dots' => $dots,
            ];
        }

        return [
            'cells' => $cells,
            'label' => ucfirst($monthStart->translatedFormat('F Y')),
            'head' => array_map(fn ($i) => __('teacher.availability.days_short.'.$i), range(0, 6)),
        ];
    }

    /**
     * @param  Collection<int, TimeOff>  $timeOffs
     * @return array{groups:array<int,array<string,mixed>>, empty:bool}
     */
    private function buildAgenda(string $displayTz, Carbon $now, $timeOffs): array
    {
        $teacher = $this->teacher();
        $today = $now->copy()->setTimezone($displayTz)->startOfDay();
        $end = $today->copy()->addDays(21);

        $slots = collect();
        if ($teacher) {
            $slots = $teacher->availabilitySlots()
                ->whereIn('status', ['available', 'booked'])
                ->whereBetween('starts_at', [$today->copy()->utc(), $end->copy()->utc()])
                ->with('lessonSession.learner')
                ->orderBy('starts_at')
                ->get();
        }

        $groups = [];
        foreach ($slots as $slot) {
            $status = $slot->isBooked() ? 'booked' : ($slot->starts_at->isPast() ? 'past' : 'open');
            if ($status === 'past') {
                continue;
            }
            if ($this->agendaFilter !== 'all' && $status !== $this->agendaFilter) {
                continue;
            }
            $local = $slot->starts_at->copy()->setTimezone($displayTz);
            $key = $local->toDateString();
            $duration = $slot->durationMinutes();
            $colors = $this->statusColors($status);
            $learnerName = trim($slot->lessonSession?->learner?->first_name.' '.$slot->lessonSession?->learner?->last_name);

            $sub = __('teacher.availability.days.'.($local->dayOfWeekIso - 1)).' · '.$duration.' min';
            if ($status === 'booked' && $learnerName) {
                $sub .= ' · '.$learnerName;
            } elseif ($status === 'open' && $slot->starts_at->lessThan($now->copy()->addDay())) {
                $sub .= ' · '.__('teacher.availability.agenda_notice');
            }

            $groups[$key]['rows'][] = [
                'id' => $slot->id,
                'day' => $local->day,
                'mon' => $local->translatedFormat('M'),
                'range' => $local->format('H:i').' – '.$local->copy()->addMinutes($duration)->format('H:i'),
                'rec' => (bool) $slot->availability_pattern_id,
                'sub' => $sub,
                'colors' => $colors,
                'label' => $status === 'booked' ? __('teacher.availability.status_booked') : __('teacher.availability.status_available'),
                'lock' => $status === 'booked',
                'canDel' => $status === 'open',
            ];
        }

        // Blocked (leave-only) days when no status filter is active.
        if ($this->agendaFilter === 'all') {
            foreach ($timeOffs as $off) {
                for ($d = $today->copy(); $d->lt($end); $d->addDay()) {
                    $ds = $d->toDateString();
                    if ($ds >= $off->starts_on->toDateString() && $ds <= $off->ends_on->toDateString() && ! isset($groups[$ds])) {
                        $groups[$ds] = ['leave' => $off->reason ?: __('teacher.availability.leave_default'), 'rows' => []];
                    }
                }
            }
        }

        ksort($groups);
        $result = [];
        foreach ($groups as $date => $group) {
            $carbon = Carbon::parse($date, $displayTz);
            $rowsList = $group['rows'] ?? [];
            $open = count(array_filter($rowsList, fn ($r) => ! $r['lock']));
            $booked = count(array_filter($rowsList, fn ($r) => $r['lock']));
            $summary = [];
            if ($open) {
                $summary[] = __('teacher.availability.count_open', ['count' => $open]);
            }
            if ($booked) {
                $summary[] = trans_choice('teacher.availability.count_booked', $booked, ['count' => $booked]);
            }

            $result[] = [
                'title' => $carbon->translatedFormat('l j F'),
                'isToday' => $date === $today->toDateString(),
                'leave' => $group['leave'] ?? null,
                'summary' => ($group['leave'] ?? null) && ! $rowsList ? __('teacher.availability.agenda_day_blocked') : implode(' · ', $summary),
                'rows' => $rowsList,
            ];
        }

        return ['groups' => $result, 'empty' => count($result) === 0];
    }

    /** @return array<int, array{key:string,label:string}> */
    private function tzOptions(): array
    {
        $options = [['key' => 'home', 'label' => __('teacher.availability.tz.home')]];
        foreach (config('timezones.preview') as $key => $iana) {
            $options[] = ['key' => $key, 'label' => __('teacher.availability.timezones.'.$key).' ('.$this->gmt($iana).')'];
        }

        return $options;
    }

    private function tzLabel(string $iana): string
    {
        foreach (config('timezones.preview') as $key => $value) {
            if ($value === $iana) {
                return __('teacher.availability.timezones.'.$key);
            }
        }

        return __('teacher.availability.tz.kabylie');
    }

    private function gmt(string $iana): string
    {
        return 'GMT'.Carbon::now($iana)->format('P');
    }

    private function tzDiff(string $iana): string
    {
        $diff = Carbon::now($iana)->utcOffset() - Carbon::now($this->teacherTz())->utcOffset();
        if ($diff === 0) {
            return __('teacher.availability.tz.same');
        }

        return ($diff > 0 ? '+' : '−').abs($diff / 60).' h';
    }

    /** @return array<int, array{city:string,gmt:string,when:string}> */
    private function previewFor(Carbon $startsAtUtc, int $duration): array
    {
        $rows = [];
        foreach (config('timezones.preview') as $key => $iana) {
            $local = $startsAtUtc->copy()->setTimezone($iana);
            $rows[] = [
                'city' => __('teacher.availability.timezones.'.$key),
                'gmt' => $this->gmt($iana),
                'when' => $local->translatedFormat('D j M').' · '.$local->format('H:i').' – '.$local->copy()->addMinutes($duration)->format('H:i'),
            ];
        }

        return $rows;
    }

    /** @return array<string, mixed> */
    private function modalData($slots, string $displayTz): array
    {
        $rec = $this->recurrenceErrors();
        $data = [
            'recErrors' => $rec,
            'onePreview' => [],
            'oneSummary' => null,
            'bookedData' => null,
            'slotData' => null,
            'deleteData' => null,
        ];

        // One-off live preview.
        try {
            $startsAt = Carbon::createFromFormat('Y-m-d H:i', "{$this->oneOff->date} {$this->oneOff->start}", $this->teacherTz());
            if ($startsAt) {
                $data['onePreview'] = $this->previewFor($startsAt->copy()->utc(), $this->oneOff->duration);
                $data['oneSummary'] = $startsAt->translatedFormat('D j M').' · '.$startsAt->format('H:i').' – '.$startsAt->copy()->addMinutes($this->oneOff->duration)->format('H:i');
            }
        } catch (\Throwable) {
            // incomplete form — no preview
        }

        $id = $this->modal['id'] ?? null;
        $slot = $id
            ? ($slots->firstWhere('id', $id) ?? $this->teacher()?->availabilitySlots()->with('lessonSession.learner.user')->find($id))
            : null;
        if ($slot) {
            $duration = $slot->durationMinutes();
            $when = $slot->starts_at->copy()->setTimezone($this->teacherTz());
            $recLabel = $slot->availability_pattern_id
                ? __('teacher.availability.recurring_on', ['day' => __('teacher.availability.days.'.($slot->starts_at->copy()->setTimezone($this->teacherTz())->dayOfWeekIso - 1))])
                : null;

            if ($slot->isBooked() && $slot->lessonSession) {
                $learner = $slot->lessonSession->learner;
                $learnerTz = $learner?->user?->timezone ?? 'Europe/Paris';
                $learnerLocal = $slot->starts_at->copy()->setTimezone($learnerTz);
                $name = trim($learner?->first_name.' '.$learner?->last_name);
                $data['bookedData'] = [
                    'name' => $name,
                    'initials' => collect(explode(' ', $name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode(''),
                    'gmt' => $this->gmt($learnerTz),
                    'teacherWhen' => $when->translatedFormat('D j M').' · '.$when->format('H:i').' – '.$when->copy()->addMinutes($duration)->format('H:i'),
                    'learnerWhen' => $learnerLocal->translatedFormat('D j M').' · '.$learnerLocal->format('H:i').' – '.$learnerLocal->copy()->addMinutes($duration)->format('H:i'),
                    'duration' => $duration,
                    'meetLink' => $this->teacher()?->meet_link,
                ];
            }

            $data['slotData'] = [
                'when' => $when->translatedFormat('l j F').' · '.$when->format('H:i').' – '.$when->copy()->addMinutes($duration)->format('H:i'),
                'rec' => (bool) $slot->availability_pattern_id,
                'recLabel' => $recLabel,
                'preview' => $this->previewFor($slot->starts_at, $duration),
            ];
            $data['deleteData'] = [
                'when' => $when->translatedFormat('D j M').' · '.$when->format('H:i'),
                'rec' => (bool) $slot->availability_pattern_id,
            ];
        }

        return $data;
    }
}
