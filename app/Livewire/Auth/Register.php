<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\RegisterForm;
use App\Models\Learner;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Register extends Component
{
    public RegisterForm $form;

    public function register(): void
    {
        $this->form->validate();

        $user = User::create([
            'name' => $this->form->name,
            'email' => $this->form->email,
            'password' => $this->form->password,
            'role' => 'learner',
        ]);

        $user->assignRole('learner');

        Learner::create([
            'user_id' => $user->id,
            'first_name' => $this->form->firstName,
            'last_name' => $this->form->lastName,
            'relationship' => 'self',
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('verification.notice'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('components.layouts.auth');
    }
}
