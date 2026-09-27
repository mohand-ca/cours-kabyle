<?php

namespace App\Filament\Widgets;

use App\Mail\TeacherApprovedMail;
use App\Models\TeacherProfile;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class PendingTeachersWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return TeacherProfile::where('status', 'pending')->exists();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('admin.stats.pending_teachers_widget'))
            ->query(
                fn (): Builder => TeacherProfile::query()
                    ->with('user')
                    ->where('status', 'pending')
                    ->orderBy('submitted_at')
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('admin.stats.columns.teacher'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label(__('admin.users.columns.email'))
                    ->searchable(),

                TextColumn::make('submitted_at')
                    ->label(__('admin.stats.columns.submitted'))
                    ->since()
                    ->sortable(),

                TextColumn::make('levels')
                    ->label(__('admin.stats.columns.levels'))
                    ->formatStateUsing(fn ($state): string => is_array($state)
                        ? implode(', ', array_map(fn ($l) => __('teacher.levels.'.$l), $state))
                        : $state),

                TextColumn::make('languages')
                    ->label(__('admin.stats.columns.languages'))
                    ->formatStateUsing(fn ($state): string => is_array($state)
                        ? implode(', ', array_map(fn ($l) => __('teacher.languages.'.$l), $state))
                        : $state),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label(__('teacher.filament.actions.approve'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->approve();
                        Mail::to($record->user->email)->queue(new TeacherApprovedMail($record->load('user')));
                    }),

                Action::make('suspend')
                    ->label(__('teacher.filament.actions.suspend'))
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->suspend()),
            ]);
    }
}
