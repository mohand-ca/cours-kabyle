<?php

namespace App\Filament\Widgets;

use App\Models\LessonSession;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestBookings extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('admin.stats.latest_bookings'))
            ->query(
                fn (): Builder => LessonSession::query()
                    ->with(['learner', 'teacherProfile.user'])
                    ->where('status', 'confirmed')
                    ->orderByDesc('created_at')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('learner.first_name')
                    ->label(__('admin.stats.columns.learner'))
                    ->formatStateUsing(fn ($record): string => $record->learner->first_name.' '.$record->learner->last_name)
                    ->searchable(query: fn (Builder $query, string $search) => $query->whereHas(
                        'learner',
                        fn ($q) => $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                    )),

                TextColumn::make('teacherProfile.user.name')
                    ->label(__('admin.stats.columns.teacher'))
                    ->searchable(),

                TextColumn::make('availabilitySlot.starts_at')
                    ->label(__('admin.stats.columns.date'))
                    ->dateTime('d M Y H:i'),

                TextColumn::make('status')
                    ->label(__('admin.stats.columns.status'))
                    ->badge()
                    ->color('success'),

                TextColumn::make('created_at')
                    ->label(__('admin.stats.columns.booked_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ]);
    }
}
