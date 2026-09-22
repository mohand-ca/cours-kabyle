<?php

namespace App\Livewire\Learner;

use App\Models\LessonSession;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public ?int $cancelSessionId = null;

    public function confirmCancel(int $sessionId): void
    {
        $this->cancelSessionId = $sessionId;
    }

    public function dismissCancel(): void
    {
        $this->cancelSessionId = null;
    }

    public function cancelSession(): void
    {
        $user = Auth::user();
        $learnerIds = $user->learners()->pluck('id');

        $session = LessonSession::whereIn('learner_id', $learnerIds)
            ->findOrFail($this->cancelSessionId);

        $session->cancel();

        if ($session->purchase_id) {
            Purchase::find($session->purchase_id)?->increment('sessions_remaining');
        }

        $this->cancelSessionId = null;
        session()->flash('message', __('learner.booking.session_cancelled'));
    }

    public function render()
    {
        $user = Auth::user();
        $learners = $user->learners()->orderBy('relationship')->get();
        $learnerIds = $learners->pluck('id');

        $upcomingSessions = LessonSession::upcoming()
            ->whereIn('learner_id', $learnerIds)
            ->get()
            ->sortBy(fn ($s) => $s->availabilitySlot->starts_at);

        return view('livewire.learner.dashboard', [
            'learners' => $learners,
            'totalPoints' => $learners->sum('points'),
            'upcomingSessions' => $upcomingSessions,
            'sessionsRemaining' => $user->sessionsRemaining(),
        ])->layout('components.layouts.auth');
    }
}
