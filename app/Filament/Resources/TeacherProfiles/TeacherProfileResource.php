<?php

namespace App\Filament\Resources\TeacherProfiles;

use App\Filament\Resources\TeacherProfiles\Pages\EditTeacherProfile;
use App\Filament\Resources\TeacherProfiles\Pages\ListTeacherProfiles;
use App\Filament\Resources\TeacherProfiles\Pages\ViewTeacherProfile;
use App\Filament\Resources\TeacherProfiles\Schemas\TeacherProfileForm;
use App\Filament\Resources\TeacherProfiles\Tables\TeacherProfilesTable;
use App\Models\TeacherProfile;
use BackedEnum;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TeacherProfileResource extends Resource
{
    protected static ?string $model = TeacherProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function getNavigationLabel(): string
    {
        return __('teacher.filament.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('teacher.filament.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('teacher.filament.plural_model_label');
    }

    public static function form(Schema $schema): Schema
    {
        return TeacherProfileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('teacher.filament.sections.stats'))
                    ->schema([
                        Grid::make(5)->schema([
                            TextEntry::make('stat_confirmed')
                                ->label(__('teacher.filament.stats.confirmed'))
                                ->getStateUsing(fn ($record): int => $record->confirmedLessonSessions()->count())
                                ->badge()
                                ->color('success'),

                            TextEntry::make('stat_upcoming')
                                ->label(__('teacher.filament.stats.upcoming'))
                                ->getStateUsing(fn ($record): int => $record->confirmedLessonSessions()
                                    ->whereHas('availabilitySlot', fn ($q) => $q->where('starts_at', '>', now()))
                                    ->count())
                                ->badge()
                                ->color('primary'),

                            TextEntry::make('stat_available')
                                ->label(__('teacher.filament.stats.available'))
                                ->getStateUsing(fn ($record): int => $record->upcomingAvailableSlots()->count())
                                ->badge()
                                ->color('info'),

                            TextEntry::make('stat_cancelled')
                                ->label(__('teacher.filament.stats.cancelled'))
                                ->getStateUsing(fn ($record): int => $record->lessonSessions()->where('status', 'cancelled')->count())
                                ->badge()
                                ->color('danger'),

                            TextEntry::make('stat_fill_rate')
                                ->label(__('teacher.filament.stats.fill_rate'))
                                ->getStateUsing(function ($record): string {
                                    $booked = $record->availabilitySlots()->where('status', 'booked')->count();
                                    $total = $record->availabilitySlots()->whereIn('status', ['available', 'booked'])->count();

                                    return $total > 0 ? round($booked / $total * 100).'%' : '0%';
                                })
                                ->badge()
                                ->color('warning'),
                        ]),
                    ]),

                Section::make(__('teacher.filament.sections.profile'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('user.name')
                                ->label(__('teacher.filament.columns.teacher')),

                            TextEntry::make('user.email')
                                ->label(__('teacher.filament.columns.email')),

                            TextEntry::make('status')
                                ->label(__('teacher.filament.columns.status'))
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'approved' => 'success',
                                    'pending' => 'warning',
                                    'suspended' => 'danger',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => __('teacher.status.'.$state)),

                            TextEntry::make('submitted_at')
                                ->label(__('teacher.filament.columns.submitted_at'))
                                ->dateTime('d M Y H:i')
                                ->placeholder('—'),

                            TextEntry::make('timezone_value')
                                ->label(__('teacher.filament.infolist.timezone'))
                                ->getStateUsing(fn ($record): string => $record->timezone()),

                            TextEntry::make('meet_link')
                                ->label(__('teacher.filament.infolist.meet_link'))
                                ->placeholder('—')
                                ->url(fn ($record): ?string => $record->meet_link ?: null),
                        ]),

                        TextEntry::make('bio')
                            ->label(__('teacher.filament.infolist.bio'))
                            ->placeholder('—')
                            ->columnSpanFull(),

                        Grid::make(2)->schema([
                            TextEntry::make('levels')
                                ->label(__('teacher.filament.infolist.levels'))
                                ->badge()
                                ->color('info')
                                ->formatStateUsing(fn (string $state): string => __('teacher.levels.'.$state))
                                ->placeholder('—'),

                            TextEntry::make('languages')
                                ->label(__('teacher.filament.infolist.languages'))
                                ->badge()
                                ->color('success')
                                ->formatStateUsing(fn (string $state): string => __('teacher.languages.'.$state))
                                ->placeholder('—'),
                        ]),
                    ]),

                Section::make(__('teacher.filament.sections.scheduling'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('default_slot_duration')
                                ->label(__('teacher.filament.infolist.default_slot_duration'))
                                ->formatStateUsing(fn (int $state): string => $state.' '.__('teacher.filament.infolist.min_unit'))
                                ->placeholder('—'),

                            TextEntry::make('slot_buffer')
                                ->label(__('teacher.filament.infolist.slot_buffer'))
                                ->formatStateUsing(fn (int $state): string => $state > 0
                                    ? $state.' '.__('teacher.filament.infolist.min_unit')
                                    : __('teacher.availability.buffer_none'))
                                ->placeholder('—'),

                            TextEntry::make('booking_horizon_weeks')
                                ->label(__('teacher.filament.infolist.booking_horizon_weeks'))
                                ->formatStateUsing(fn (int $state): string => $state.' '.__('teacher.filament.infolist.weeks_unit'))
                                ->placeholder('—'),
                        ]),
                    ]),

                Section::make(__('teacher.filament.sections.upcoming'))
                    ->schema([
                        RepeatableEntry::make('upcoming_booked_sessions')
                            ->label('')
                            ->getStateUsing(fn ($record) => $record->confirmedLessonSessions()
                                ->with(['learner', 'availabilitySlot'])
                                ->whereHas('availabilitySlot', fn ($q) => $q->where('starts_at', '>', now()))
                                ->get()
                                ->sortBy(fn ($s) => $s->availabilitySlot?->starts_at)
                                ->map(fn ($session) => [
                                    'learner_name' => ($session->learner?->first_name ?? '').' '.($session->learner?->last_name ?? ''),
                                    'starts_at' => $session->availabilitySlot?->starts_at?->format('d M Y H:i') ?? '—',
                                    'duration' => $session->availabilitySlot
                                        ? $session->availabilitySlot->durationMinutes().' '.__('teacher.filament.infolist.min_unit')
                                        : '—',
                                    'booked_at' => $session->created_at?->format('d M Y') ?? '—',
                                ])
                                ->values()
                                ->all()
                            )
                            ->table([
                                TableColumn::make(__('teacher.filament.infolist.learner')),
                                TableColumn::make(__('admin.lesson_sessions.columns.starts_at')),
                                TableColumn::make(__('teacher.filament.infolist.duration')),
                                TableColumn::make(__('admin.lesson_sessions.columns.booked_at')),
                            ])
                            ->schema([
                                TextEntry::make('learner_name')
                                    ->label(__('teacher.filament.infolist.learner')),

                                TextEntry::make('starts_at')
                                    ->label(__('admin.lesson_sessions.columns.starts_at')),

                                TextEntry::make('duration')
                                    ->label(__('teacher.filament.infolist.duration')),

                                TextEntry::make('booked_at')
                                    ->label(__('admin.lesson_sessions.columns.booked_at')),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make(__('teacher.filament.sections.patterns'))
                    ->schema([
                        RepeatableEntry::make('availabilityPatterns')
                            ->label('')
                            ->getStateUsing(fn ($record) => $record->availabilityPatterns()->orderBy('day_of_week')->get())
                            ->table([
                                TableColumn::make(__('teacher.filament.infolist.day')),
                                TableColumn::make(__('teacher.filament.infolist.start_time')),
                                TableColumn::make(__('teacher.filament.infolist.end_time')),
                                TableColumn::make(__('teacher.filament.infolist.slot_duration')),
                                TableColumn::make(__('teacher.filament.infolist.buffer')),
                                TableColumn::make(__('teacher.filament.infolist.from')),
                                TableColumn::make(__('teacher.filament.infolist.until')),
                                TableColumn::make(__('teacher.filament.infolist.active')),
                            ])
                            ->schema([
                                TextEntry::make('day_of_week')
                                    ->label(__('teacher.filament.infolist.day'))
                                    ->formatStateUsing(fn (int $state): string => __('teacher.availability.days.'.$state)),

                                TextEntry::make('start_time')
                                    ->label(__('teacher.filament.infolist.start_time')),

                                TextEntry::make('end_time')
                                    ->label(__('teacher.filament.infolist.end_time')),

                                TextEntry::make('slot_duration')
                                    ->label(__('teacher.filament.infolist.slot_duration'))
                                    ->formatStateUsing(fn (int $state): string => $state.' '.__('teacher.filament.infolist.min_unit')),

                                TextEntry::make('buffer')
                                    ->label(__('teacher.filament.infolist.buffer'))
                                    ->formatStateUsing(fn (int $state): string => $state > 0
                                        ? $state.' '.__('teacher.filament.infolist.min_unit')
                                        : __('teacher.availability.buffer_none')),

                                TextEntry::make('starts_on')
                                    ->label(__('teacher.filament.infolist.from'))
                                    ->formatStateUsing(fn ($state): string => $state?->format('d M Y') ?? __('teacher.filament.infolist.always')),

                                TextEntry::make('until')
                                    ->label(__('teacher.filament.infolist.until'))
                                    ->formatStateUsing(fn ($state): string => $state?->format('d M Y') ?? __('teacher.filament.infolist.no_limit')),

                                IconEntry::make('is_active')
                                    ->label(__('teacher.filament.infolist.active'))
                                    ->boolean(),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make(__('teacher.filament.sections.time_offs'))
                    ->schema([
                        RepeatableEntry::make('timeOffs')
                            ->label('')
                            ->getStateUsing(fn ($record) => $record->timeOffs()->orderBy('starts_on')->get())
                            ->table([
                                TableColumn::make(__('teacher.filament.infolist.from')),
                                TableColumn::make(__('teacher.filament.infolist.until')),
                                TableColumn::make(__('teacher.filament.infolist.days_count')),
                                TableColumn::make(__('teacher.filament.infolist.reason')),
                            ])
                            ->schema([
                                TextEntry::make('starts_on')
                                    ->label(__('teacher.filament.infolist.from'))
                                    ->date('d M Y'),

                                TextEntry::make('ends_on')
                                    ->label(__('teacher.filament.infolist.until'))
                                    ->date('d M Y'),

                                TextEntry::make('duration_days')
                                    ->label(__('teacher.filament.infolist.days_count'))
                                    ->getStateUsing(fn ($record): int => $record->ends_on->diffInDays($record->starts_on) + 1),

                                TextEntry::make('reason')
                                    ->label(__('teacher.filament.infolist.reason'))
                                    ->placeholder('—'),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make(__('teacher.filament.sections.history'))
                    ->schema([
                        RepeatableEntry::make('past_sessions')
                            ->label('')
                            ->getStateUsing(fn ($record) => $record->lessonSessions()
                                ->with(['learner', 'availabilitySlot'])
                                ->whereHas('availabilitySlot', fn ($q) => $q->where('starts_at', '<=', now()))
                                ->get()
                                ->sortByDesc(fn ($s) => $s->availabilitySlot?->starts_at)
                                ->take(50)
                                ->map(fn ($session) => [
                                    'learner_name' => ($session->learner?->first_name ?? '').' '.($session->learner?->last_name ?? ''),
                                    'starts_at' => $session->availabilitySlot?->starts_at?->format('d M Y H:i') ?? '—',
                                    'duration' => $session->availabilitySlot
                                        ? $session->availabilitySlot->durationMinutes().' '.__('teacher.filament.infolist.min_unit')
                                        : '—',
                                    'status' => $session->status,
                                ])
                                ->values()
                                ->all()
                            )
                            ->table([
                                TableColumn::make(__('teacher.filament.infolist.learner')),
                                TableColumn::make(__('admin.lesson_sessions.columns.starts_at')),
                                TableColumn::make(__('teacher.filament.infolist.duration')),
                                TableColumn::make(__('admin.lesson_sessions.columns.status')),
                            ])
                            ->schema([
                                TextEntry::make('learner_name')
                                    ->label(__('teacher.filament.infolist.learner')),

                                TextEntry::make('starts_at')
                                    ->label(__('admin.lesson_sessions.columns.starts_at')),

                                TextEntry::make('duration')
                                    ->label(__('teacher.filament.infolist.duration')),

                                TextEntry::make('status')
                                    ->label(__('admin.lesson_sessions.columns.status'))
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'confirmed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => __('admin.lesson_sessions.statuses.'.$state)),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return TeacherProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeacherProfiles::route('/'),
            'view' => ViewTeacherProfile::route('/{record}'),
            'edit' => EditTeacherProfile::route('/{record}/edit'),
        ];
    }
}
