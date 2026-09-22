<?php

namespace App\Livewire\Learner;

use App\Models\SessionPackage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PackageCatalog extends Component
{
    public function render()
    {
        $packages = SessionPackage::active()->orderBy('sessions_count')->get();
        $sessionsRemaining = Auth::user()->sessionsRemaining();

        return view('livewire.learner.package-catalog', [
            'packages' => $packages,
            'sessionsRemaining' => $sessionsRemaining,
        ])->layout('components.layouts.app');
    }
}
