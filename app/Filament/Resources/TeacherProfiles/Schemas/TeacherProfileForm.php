<?php

namespace App\Filament\Resources\TeacherProfiles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeacherProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('teacher.filament.section'))
                    ->schema([
                        Textarea::make('bio')
                            ->label(__('teacher.profile.bio'))
                            ->rows(5)
                            ->maxLength(2000),

                        Select::make('levels')
                            ->label(__('teacher.profile.levels'))
                            ->multiple()
                            ->options([
                                'beginner' => __('teacher.levels.beginner'),
                                'intermediate' => __('teacher.levels.intermediate'),
                                'advanced' => __('teacher.levels.advanced'),
                            ]),

                        Select::make('languages')
                            ->label(__('teacher.profile.languages'))
                            ->multiple()
                            ->options([
                                'kabyle' => __('teacher.languages.kabyle'),
                                'french' => __('teacher.languages.french'),
                                'english' => __('teacher.languages.english'),
                                'arabic' => __('teacher.languages.arabic'),
                                'other' => __('teacher.languages.other'),
                            ]),

                        TextInput::make('meet_link')
                            ->label(__('teacher.profile.meet_link'))
                            ->url()
                            ->maxLength(255),

                        Select::make('status')
                            ->label(__('teacher.filament.columns.status'))
                            ->options([
                                'pending' => __('teacher.status.pending'),
                                'approved' => __('teacher.status.approved'),
                                'suspended' => __('teacher.status.suspended'),
                            ])
                            ->required(),
                    ]),
            ]);
    }
}
