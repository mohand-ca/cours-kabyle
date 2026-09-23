<?php

namespace App\Livewire\Learner;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PackageCatalog extends Component
{
    public function render()
    {
        return view('livewire.learner.package-catalog', [
            'packages' => config('packages'),
            'sessionsRemaining' => Auth::user()->sessionsRemaining(),
        ])->layout('components.layouts.app');
    }
}
