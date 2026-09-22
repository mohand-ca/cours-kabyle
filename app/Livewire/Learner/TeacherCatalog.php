<?php

namespace App\Livewire\Learner;

use App\Models\TeacherProfile;
use Livewire\Component;

class TeacherCatalog extends Component
{
    public string $filterLevel = '';

    public string $filterLanguage = '';

    public const LEVEL_OPTIONS = ['beginner', 'intermediate', 'advanced'];

    public const LANGUAGE_OPTIONS = ['kabyle', 'french', 'english', 'arabic', 'other'];

    public function updatedFilterLevel(): void
    {
        // Reactivity handled by Livewire re-render
    }

    public function updatedFilterLanguage(): void
    {
        // Reactivity handled by Livewire re-render
    }

    public function render()
    {
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

        return view('livewire.learner.teacher-catalog', [
            'teachers' => $teachers,
        ])->layout('components.layouts.auth');
    }
}
