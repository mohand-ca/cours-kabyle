<?php

namespace App\Livewire\Teacher;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $profile = Auth::user()->teacherProfile;

        return view('livewire.teacher.dashboard', [
            'profile' => $profile,
            'upcomingSlots' => $profile?->availabilitySlots()
                ->where('status', 'booked')
                ->where('starts_at', '>', now())
                ->orderBy('starts_at')
                ->limit(5)
                ->get() ?? collect(),
        ])->layout('components.layouts.auth');
    }
}
