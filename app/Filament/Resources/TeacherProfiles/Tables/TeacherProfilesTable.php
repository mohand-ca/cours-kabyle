<?php

namespace App\Filament\Resources\TeacherProfiles\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TeacherProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('teacher.filament.columns.teacher'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label(__('teacher.filament.columns.email'))
                    ->searchable(),

                TextColumn::make('status')
                    ->label(__('teacher.filament.columns.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'suspended' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => __('teacher.status.'.$state)),

                TextColumn::make('submitted_at')
                    ->label(__('teacher.filament.columns.submitted_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('availabilitySlots_count')
                    ->label(__('teacher.filament.columns.slots_count'))
                    ->counts('availabilitySlots')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('teacher.filament.columns.status'))
                    ->options([
                        'pending' => __('teacher.status.pending'),
                        'approved' => __('teacher.status.approved'),
                        'suspended' => __('teacher.status.suspended'),
                    ]),
            ])
            ->defaultSort('submitted_at', 'asc')
            ->recordActions([
                Action::make('approve')
                    ->label(__('teacher.filament.actions.approve'))
                    ->icon(Heroicon::Check ?? 'heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => ! $record->isApproved())
                    ->action(fn ($record) => $record->approve()),

                Action::make('suspend')
                    ->label(__('teacher.filament.actions.suspend'))
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->isApproved())
                    ->action(fn ($record) => $record->suspend()),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
