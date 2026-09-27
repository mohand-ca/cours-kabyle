<?php

namespace App\Filament\Resources\LessonSessions\Pages;

use App\Filament\Resources\LessonSessions\LessonSessionResource;
use Filament\Resources\Pages\ListRecords;

class ListLessonSessions extends ListRecords
{
    protected static string $resource = LessonSessionResource::class;
}
