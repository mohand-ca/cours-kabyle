<?php

namespace App\Livewire\Learner;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $learners = $user->learners()->orderBy('relationship')->get();

        return view('livewire.learner.dashboard', [
            'learners' => $learners,
            'totalPoints' => $learners->sum('points'),
        ])->layout('components.layouts.auth');
    }
}
