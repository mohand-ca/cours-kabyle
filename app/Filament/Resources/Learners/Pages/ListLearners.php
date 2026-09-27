<?php

namespace App\Filament\Resources\Learners\Pages;

use App\Filament\Resources\Learners\LearnerResource;
use Filament\Resources\Pages\ListRecords;

class ListLearners extends ListRecords
{
    protected static string $resource = LearnerResource::class;
}
