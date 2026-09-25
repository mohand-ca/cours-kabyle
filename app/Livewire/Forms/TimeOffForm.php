<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class TimeOffForm extends Form
{
    #[Validate('required|date')]
    public string $from = '';

    #[Validate('required|date')]
    public string $to = '';

    #[Validate('nullable|string|max:120')]
    public ?string $reason = null;
}
