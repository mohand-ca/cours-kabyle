<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', __('auth.failed'));

            return;
        }

        session()->regenerate();

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $this->redirect(route('filament.admin.pages.dashboard'), navigate: false);

            return;
        }

        if ($user->hasRole('teacher')) {
            $this->redirect(route('teacher.dashboard'), navigate: true);

            return;
        }

        $this->redirect(route('learner.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth');
    }
}
