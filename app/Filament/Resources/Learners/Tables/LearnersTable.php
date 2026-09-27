<?php

namespace App\Filament\Resources\Learners\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LearnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label(__('admin.learners.columns.name'))
                    ->formatStateUsing(fn ($record): string => $record->first_name.' '.$record->last_name)
                    ->searchable(['first_name', 'last_name']),

                TextColumn::make('user.name')
                    ->label(__('admin.learners.columns.parent'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('relationship')
                    ->label(__('admin.learners.columns.relationship'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'self' => 'primary',
                        'child' => 'info',
                        'spouse' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.learners.relationships.'.$state)),

                TextColumn::make('points')
                    ->label(__('admin.learners.columns.points'))
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('lesson_sessions_count')
                    ->label(__('admin.learners.columns.sessions_count'))
                    ->counts('lessonSessions')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label(__('admin.learners.columns.created_at'))
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('relationship')
                    ->label(__('admin.learners.filters.relationship'))
                    ->options([
                        'self' => __('admin.learners.relationships.self'),
                        'child' => __('admin.learners.relationships.child'),
                        'spouse' => __('admin.learners.relationships.spouse'),
                        'other' => __('admin.learners.relationships.other'),
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
