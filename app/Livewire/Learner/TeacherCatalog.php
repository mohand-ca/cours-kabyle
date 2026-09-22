<?php

namespace App\Livewire\Learner;

use App\Models\AvailabilitySlot;
use App\Models\LessonSession;
use App\Models\TeacherProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TeacherCatalog extends Component
{
    public string $filterLevel = '';

    public string $filterLanguage = '';

    public ?int $expandedTeacherId = null;

    public ?int $selectedSlotId = null;

    public ?int $selectedLearnerId = null;

    public const LEVEL_OPTIONS = ['beginner', 'intermediate', 'advanced'];

    public const LANGUAGE_OPTIONS = ['kabyle', 'french', 'english', 'arabic', 'other'];

    public function toggleSlots(int $teacherId): void
    {
        $this->expandedTeacherId = ($this->expandedTeacherId === $teacherId) ? null : $teacherId;
        $this->selectedSlotId = null;
        $this->selectedLearnerId = null;
    }

    public function selectSlot(int $slotId): void
    {
        $this->selectedSlotId = $slotId;
        $this->selectedLearnerId = null;
    }

    public function cancelBooking(): void
    {
        $this->selectedSlotId = null;
        $this->selectedLearnerId = null;
    }

    public function book(): void
    {
        $this->validate(['selectedLearnerId' => 'required|integer']);

        $user = Auth::user();
        $learner = $user->learners()->findOrFail($this->selectedLearnerId);

        $purchase = $user->purchases()
            ->where('status', 'completed')
            ->where('sessions_remaining', '>', 0)
            ->oldest()
            ->first();

        if (! $purchase) {
            $this->addError('selectedLearnerId', __('learner.packages.no_sessions_remaining'));

            return;
        }

        $slotTaken = false;

        DB::transaction(function () use ($learner, $purchase, &$slotTaken) {
            $slot = AvailabilitySlot::lockForUpdate()->findOrFail($this->selectedSlotId);

            if (! $slot->isAvailable()) {
                $slotTaken = true;

                return;
            }

            LessonSession::create([
                'availability_slot_id' => $slot->id,
                'learner_id' => $learner->id,
                'teacher_profile_id' => $slot->teacher_profile_id,
                'purchase_id' => $purchase->id,
                'status' => 'confirmed',
            ]);

            $slot->book();
            $purchase->decrement('sessions_remaining');
        });

        if ($slotTaken) {
            $this->addError('selectedSlotId', __('learner.booking.slot_taken'));

            return;
        }

        $this->expandedTeacherId = null;
        $this->selectedSlotId = null;
        $this->selectedLearnerId = null;
        session()->flash('message', __('learner.booking.booked'));
    }

    public function render()
    {
        $user = Auth::user();

        $query = TeacherProfile::query()
            ->where('status', 'approved')
            ->with('user')
            ->withCount([
                'availabilitySlots as available_slots_count' => function ($q) {
                    $q->where('status', 'available')->where('starts_at', '>', now());
                },
            ]);

        if ($this->filterLevel) {
            $query->whereJsonContains('levels', $this->filterLevel);
        }

        if ($this->filterLanguage) {
            $query->whereJsonContains('languages', $this->filterLanguage);
        }

        $teachers = $query->orderByDesc('available_slots_count')->get();

        $availableSlots = $this->expandedTeacherId
            ? AvailabilitySlot::where('teacher_profile_id', $this->expandedTeacherId)
                ->available()
                ->orderBy('starts_at')
                ->get()
            : collect();

        $learners = $user->learners()->orderBy('relationship')->get();

        return view('livewire.learner.teacher-catalog', [
            'teachers' => $teachers,
            'availableSlots' => $availableSlots,
            'learners' => $learners,
        ])->layout('components.layouts.app');
    }
}
