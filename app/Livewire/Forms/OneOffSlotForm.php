<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class OneOffSlotForm extends Form
{
    #[Validate('required|date')]
    public string $date = '';

    #[Validate('required|date_format:H:i')]
    public string $start = '';

    #[Validate('required|integer|in:60,90,120')]
    public int $duration = 60;
}
