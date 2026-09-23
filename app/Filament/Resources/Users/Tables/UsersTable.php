<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('admin.users.columns.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('admin.users.columns.email'))
                    ->searchable(),

                TextColumn::make('role')
                    ->label(__('admin.users.columns.role'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'teacher' => 'info',
                        'learner' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.users.roles.'.$state)),

                IconColumn::make('email_verified_at')
                    ->label(__('admin.users.columns.verified'))
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => $record->hasVerifiedEmail()),

                TextColumn::make('timezone')
                    ->label(__('admin.users.columns.timezone'))
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('admin.users.columns.created_at'))
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label(__('admin.users.filters.role'))
                    ->options([
                        'admin' => __('admin.users.roles.admin'),
                        'teacher' => __('admin.users.roles.teacher'),
                        'learner' => __('admin.users.roles.learner'),
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
