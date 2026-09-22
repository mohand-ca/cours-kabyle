<?php

namespace App\Livewire\Forms;

use App\Models\Learner;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LearnerForm extends Form
{
    #[Validate('required|string|max:50')]
    public string $firstName = '';

    #[Validate('required|string|max:50')]
    public string $lastName = '';

    #[Validate('required|in:self,child,spouse,other')]
    public string $relationship = 'child';

    #[Validate('nullable|date|before:today')]
    public ?string $dateOfBirth = null;

    #[Validate('nullable|email|max:255')]
    public ?string $notificationEmail = null;

    public function populate(Learner $learner): void
    {
        $this->firstName = $learner->first_name;
        $this->lastName = $learner->last_name;
        $this->relationship = $learner->relationship;
        $this->dateOfBirth = $learner->date_of_birth;
        $this->notificationEmail = $learner->notification_email;
    }
}
