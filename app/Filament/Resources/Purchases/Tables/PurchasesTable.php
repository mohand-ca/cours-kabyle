<?php

namespace App\Filament\Resources\Purchases\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Lang;

class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('admin.purchases.columns.user'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label(__('admin.users.columns.email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('package_key')
                    ->label(__('admin.purchases.columns.package'))
                    ->formatStateUsing(fn (string $state): string => Lang::has('admin.purchases.packages.'.$state) ? __('admin.purchases.packages.'.$state) : $state)
                    ->badge()
                    ->color('warning'),

                TextColumn::make('sessions_total')
                    ->label(__('admin.purchases.columns.sessions_total'))
                    ->alignCenter(),

                TextColumn::make('sessions_remaining')
                    ->label(__('admin.purchases.columns.sessions_remaining'))
                    ->alignCenter()
                    ->color(fn (int $state): string => $state === 0 ? 'danger' : 'success'),

                TextColumn::make('status')
                    ->label(__('admin.purchases.columns.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'refunded' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.purchases.statuses.'.$state)),

                TextColumn::make('stripe_session_id')
                    ->label(__('admin.purchases.columns.stripe_session_id'))
                    ->limit(24)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('admin.purchases.columns.created_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('admin.purchases.filters.status'))
                    ->options([
                        'pending' => __('admin.purchases.statuses.pending'),
                        'completed' => __('admin.purchases.statuses.completed'),
                        'refunded' => __('admin.purchases.statuses.refunded'),
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
