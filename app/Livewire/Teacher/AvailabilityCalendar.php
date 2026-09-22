<?php

namespace App\Livewire\Teacher;

use App\Models\AvailabilitySlot;
use App\Models\TeacherProfile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AvailabilityCalendar extends Component
{
    #[Validate('required|date|after:today')]
    public string $date = '';

    #[Validate('required|date_format:H:i')]
    public string $startTime = '';

    #[Validate('required|integer|in:60,90,120')]
    public int $duration = 60;

    public function addSlot(): void
    {
        $this->validate();

        $teacher = $this->teacherProfile();

        if (! $teacher?->isApproved()) {
            $this->addError('date', __('teacher.profile.not_approved_error'));

            return;
        }

        $startsAt = Carbon::createFromFormat('Y-m-d H:i', "{$this->date} {$this->startTime}", Auth::user()->timezone)
            ->utc();

        $endsAt = $startsAt->copy()->addMinutes($this->duration);

        $overlapping = $teacher->availabilitySlots()
            ->whereIn('status', ['available', 'booked'])
            ->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt)
            ->exists();

        if ($overlapping) {
            $this->addError('startTime', __('teacher.availability.overlap_error'));

            return;
        }

        AvailabilitySlot::create([
            'teacher_profile_id' => $teacher->id,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'available',
        ]);

        $this->reset('date', 'startTime', 'duration');
        $this->duration = 60;

        session()->flash('success', __('teacher.availability.slot_added'));
    }

    public function cancelSlot(int $slotId): void
    {
        $teacher = $this->teacherProfile();

        $slot = $teacher->availabilitySlots()->findOrFail($slotId);

        if ($slot->isBooked()) {
            session()->flash('error', __('teacher.availability.slot_booked_error'));

            return;
        }

        $slot->update(['status' => 'cancelled']);
    }

    public function render()
    {
        $teacher = $this->teacherProfile();

        $slots = $teacher?->availabilitySlots()
            ->whereIn('status', ['available', 'booked'])
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->get();

        return view('livewire.teacher.availability-calendar', [
            'slots' => $slots ?? collect(),
            'profile' => $teacher,
        ])->layout('components.layouts.app');
    }

    private function teacherProfile(): ?TeacherProfile
    {
        return Auth::user()->teacherProfile;
    }
}
