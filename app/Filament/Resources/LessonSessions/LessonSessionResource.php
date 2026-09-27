<?php

namespace App\Filament\Resources\LessonSessions;

use App\Filament\Resources\LessonSessions\Pages\ListLessonSessions;
use App\Filament\Resources\LessonSessions\Pages\ViewLessonSession;
use App\Filament\Resources\LessonSessions\Tables\LessonSessionsTable;
use App\Models\LessonSession;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LessonSessionResource extends Resource
{
    protected static ?string $model = LessonSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    public static function getNavigationLabel(): string
    {
        return __('admin.lesson_sessions.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('admin.lesson_sessions.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.lesson_sessions.plural_model_label');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.lesson_sessions.sections.details'))
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('learner.first_name')
                                ->label(__('admin.lesson_sessions.columns.learner'))
                                ->formatStateUsing(fn ($record): string => $record->learner->first_name.' '.$record->learner->last_name),

                            TextEntry::make('teacherProfile.user.name')
                                ->label(__('admin.lesson_sessions.columns.teacher')),

                            TextEntry::make('availabilitySlot.starts_at')
                                ->label(__('admin.lesson_sessions.columns.starts_at'))
                                ->dateTime('d M Y H:i'),

                            TextEntry::make('status')
                                ->label(__('admin.lesson_sessions.columns.status'))
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'confirmed' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => __('admin.lesson_sessions.statuses.'.$state)),

                            TextEntry::make('created_at')
                                ->label(__('admin.lesson_sessions.columns.booked_at'))
                                ->dateTime('d M Y H:i'),

                            TextEntry::make('reminder_sent_at')
                                ->label(__('admin.lesson_sessions.columns.reminder_sent_at'))
                                ->dateTime('d M Y H:i')
                                ->placeholder('—'),
                        ]),
                    ]),

                Section::make(__('admin.lesson_sessions.sections.package'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('purchase.package_key')
                                ->label(__('admin.lesson_sessions.columns.package'))
                                ->badge()
                                ->color('warning')
                                ->formatStateUsing(fn (?string $state): string => $state
                                    ? __('admin.purchases.packages.'.$state)
                                    : '—')
                                ->placeholder('—'),

                            TextEntry::make('purchase.sessions_total')
                                ->label(__('admin.purchases.columns.sessions_total'))
                                ->placeholder('—'),

                            TextEntry::make('purchase.sessions_remaining')
                                ->label(__('admin.purchases.columns.sessions_remaining'))
                                ->color(fn (?int $state): string => match (true) {
                                    $state === null => 'gray',
                                    $state === 0 => 'danger',
                                    default => 'success',
                                })
                                ->placeholder('—'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return LessonSessionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLessonSessions::route('/'),
            'view' => ViewLessonSession::route('/{record}'),
        ];
    }
}
