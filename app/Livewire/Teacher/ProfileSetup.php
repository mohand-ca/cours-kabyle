<?php

namespace App\Livewire\Teacher;

use App\Models\TeacherProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProfileSetup extends Component
{
    #[Validate('required|string|min:50|max:2000')]
    public string $bio = '';

    #[Validate('required|array|min:1')]
    public array $levels = [];

    #[Validate('required|array|min:1')]
    public array $languages = [];

    #[Validate('required|url|max:255')]
    public string $meetLink = '';

    public const LEVEL_OPTIONS = ['beginner', 'intermediate', 'advanced'];

    public const LANGUAGE_OPTIONS = ['kabyle', 'french', 'english', 'arabic', 'other'];

    public function mount(): void
    {
        $profile = Auth::user()->teacherProfile;

        if ($profile) {
            $this->bio = $profile->bio ?? '';
            $this->levels = $profile->levels ?? [];
            $this->languages = $profile->languages ?? [];
            $this->meetLink = $profile->meet_link ?? '';
        }
    }

    public function save(): void
    {
        $this->validate();

        $profile = Auth::user()->teacherProfile ?? new TeacherProfile(['user_id' => Auth::id()]);

        $profile->fill([
            'bio' => $this->bio,
            'levels' => $this->levels,
            'languages' => $this->languages,
            'meet_link' => $this->meetLink,
        ])->save();

        session()->flash('success', __('teacher.profile.saved'));
    }

    public function submit(): void
    {
        $this->validate();

        $profile = Auth::user()->teacherProfile;

        if (! $profile || ! $profile->meet_link) {
            $this->addError('meetLink', __('teacher.profile.submit_error'));

            return;
        }

        $profile->submit();

        session()->flash('success', __('teacher.profile.submitted'));
    }

    public function render()
    {
        return view('livewire.teacher.profile-setup', [
            'profile' => Auth::user()->teacherProfile,
        ])->layout('components.layouts.auth');
    }
}
