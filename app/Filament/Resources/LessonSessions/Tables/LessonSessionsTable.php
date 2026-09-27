<?php

namespace App\Filament\Resources\LessonSessions\Tables;

use App\Models\TeacherProfile;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LessonSessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('learner.first_name')
                    ->label(__('admin.lesson_sessions.columns.learner'))
                    ->formatStateUsing(fn ($record): string => $record->learner->first_name.' '.$record->learner->last_name)
                    ->searchable(query: fn (Builder $query, string $search) => $query->whereHas(
                        'learner',
                        fn ($q) => $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                    )),

                TextColumn::make('teacherProfile.user.name')
                    ->label(__('admin.lesson_sessions.columns.teacher'))
                    ->searchable(),

                TextColumn::make('availabilitySlot.starts_at')
                    ->label(__('admin.lesson_sessions.columns.starts_at'))
                    ->dateTime('d M Y H:i'),

                TextColumn::make('status')
                    ->label(__('admin.lesson_sessions.columns.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.lesson_sessions.statuses.'.$state)),

                TextColumn::make('purchase.package_key')
                    ->label(__('admin.lesson_sessions.columns.package'))
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn (?string $state): string => $state
                        ? __('admin.purchases.packages.'.$state)
                        : '—')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label(__('admin.lesson_sessions.columns.booked_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('admin.lesson_sessions.filters.status'))
                    ->options([
                        'confirmed' => __('admin.lesson_sessions.statuses.confirmed'),
                        'cancelled' => __('admin.lesson_sessions.statuses.cancelled'),
                    ]),

                SelectFilter::make('teacher_profile_id')
                    ->label(__('admin.lesson_sessions.filters.teacher'))
                    ->options(
                        TeacherProfile::with('user')
                            ->whereHas('user')
                            ->get()
                            ->pluck('user.name', 'id')
                    ),

                Filter::make('upcoming')
                    ->label(__('admin.lesson_sessions.filters.upcoming'))
                    ->query(fn (Builder $query) => $query->whereHas(
                        'availabilitySlot',
                        fn ($q) => $q->where('starts_at', '>', now())
                    ))
                    ->toggle(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
