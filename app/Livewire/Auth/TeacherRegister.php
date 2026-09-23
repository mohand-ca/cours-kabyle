<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\TeacherRegisterForm;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeacherRegister extends Component
{
    public TeacherRegisterForm $form;

    public function register(): void
    {
        $this->form->validate();

        $user = User::create([
            'name' => $this->form->name,
            'email' => $this->form->email,
            'password' => $this->form->password,
            'role' => 'teacher',
            'timezone' => $this->form->timezone,
        ]);

        $user->assignRole('teacher');

        TeacherProfile::create(['user_id' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('verification.notice'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.teacher-register')
            ->layout('components.layouts.auth');
    }
}
