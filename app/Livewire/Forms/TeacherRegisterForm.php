<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class TeacherRegisterForm extends Form
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $timezone = 'Africa/Algiers';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'timezone' => ['required', 'timezone:all'],
        ];
    }
}
