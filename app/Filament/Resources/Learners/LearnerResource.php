<?php

namespace App\Filament\Resources\Learners;

use App\Filament\Resources\Learners\Pages\ListLearners;
use App\Filament\Resources\Learners\Pages\ViewLearner;
use App\Filament\Resources\Learners\Tables\LearnersTable;
use App\Models\Learner;
use BackedEnum;
use Carbon\Carbon;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LearnerResource extends Resource
{
    protected static ?string $model = Learner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function getNavigationLabel(): string
    {
        return __('admin.learners.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('admin.learners.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.learners.plural_model_label');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.learners.sections.stats'))
                    ->schema([
                        Grid::make(5)->schema([
                            TextEntry::make('stat_confirmed')
                                ->label(__('admin.learners.stats.confirmed'))
                                ->getStateUsing(fn ($record): int => $record->lessonSessions()->where('status', 'confirmed')->count())
                                ->badge()
                                ->color('success'),

                            TextEntry::make('stat_cancelled')
                                ->label(__('admin.learners.stats.cancelled'))
                                ->getStateUsing(fn ($record): int => $record->lessonSessions()->where('status', 'cancelled')->count())
                                ->badge()
                                ->color('danger'),

                            TextEntry::make('stat_upcoming')
                                ->label(__('admin.learners.stats.upcoming'))
                                ->getStateUsing(fn ($record): int => $record->lessonSessions()
                                    ->where('status', 'confirmed')
                                    ->whereHas('availabilitySlot', fn ($q) => $q->where('starts_at', '>', now()))
                                    ->count())
                                ->badge()
                                ->color('primary'),

                            TextEntry::make('stat_remaining')
                                ->label(__('admin.learners.stats.remaining'))
                                ->getStateUsing(fn ($record): int => $record->user->sessionsRemaining())
                                ->badge()
                                ->color('warning'),

                            TextEntry::make('stat_cancellation_rate')
                                ->label(__('admin.learners.stats.cancellation_rate'))
                                ->getStateUsing(function ($record): string {
                                    $confirmed = $record->lessonSessions()->where('status', 'confirmed')->count();
                                    $cancelled = $record->lessonSessions()->where('status', 'cancelled')->count();
                                    $total = $confirmed + $cancelled;

                                    return $total > 0 ? round($cancelled / $total * 100).'%' : '0%';
                                })
                                ->badge()
                                ->color('gray'),
                        ]),
                    ]),

                Section::make(__('admin.learners.sections.info'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('first_name')
                                ->label(__('admin.learners.columns.name'))
                                ->formatStateUsing(fn ($record): string => $record->first_name.' '.$record->last_name),

                            TextEntry::make('relationship')
                                ->label(__('admin.learners.columns.relationship'))
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'self' => 'primary',
                                    'child' => 'info',
                                    'spouse' => 'success',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => __('admin.learners.relationships.'.$state)),

                            TextEntry::make('age')
                                ->label(__('admin.learners.columns.age'))
                                ->getStateUsing(fn ($record): string => $record->date_of_birth
                                    ? Carbon::parse($record->date_of_birth)->diffInYears(now()).' ans'
                                    : '—'),

                            TextEntry::make('date_of_birth')
                                ->label(__('admin.learners.columns.date_of_birth'))
                                ->date('d M Y')
                                ->placeholder('—'),

                            TextEntry::make('notification_email')
                                ->label(__('admin.learners.columns.notification_email'))
                                ->placeholder('—'),

                            TextEntry::make('points')
                                ->label(__('admin.learners.columns.points'))
                                ->badge()
                                ->color('warning'),

                            TextEntry::make('created_at')
                                ->label(__('admin.learners.columns.created_at'))
                                ->date('d M Y'),
                        ]),
                    ]),

                Section::make(__('admin.learners.sections.account'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('user.name')
                                ->label(__('admin.users.columns.name')),

                            TextEntry::make('user.email')
                                ->label(__('admin.users.columns.email')),

                            TextEntry::make('user_sessions_remaining')
                                ->label(__('admin.learners.stats.remaining'))
                                ->getStateUsing(fn ($record): int => $record->user->sessionsRemaining())
                                ->badge()
                                ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray'),
                        ]),

                        RepeatableEntry::make('user_purchases')
                            ->label(__('admin.users.sections.purchases'))
                            ->getStateUsing(fn ($record) => $record->user->purchases()
                                ->orderByDesc('created_at')
                                ->get()
                                ->map(fn ($p) => [
                                    'package_key' => $p->package_key,
                                    'sessions_total' => $p->sessions_total,
                                    'sessions_remaining' => $p->sessions_remaining,
                                    'sessions_used' => $p->sessions_total - $p->sessions_remaining,
                                    'status' => $p->status,
                                    'created_at' => $p->created_at->format('d M Y'),
                                ])
                                ->all()
                            )
                            ->table([
                                TableColumn::make(__('admin.purchases.columns.package')),
                                TableColumn::make(__('admin.purchases.columns.sessions_total')),
                                TableColumn::make(__('admin.purchases.columns.sessions_used')),
                                TableColumn::make(__('admin.purchases.columns.sessions_remaining')),
                                TableColumn::make(__('admin.purchases.columns.status')),
                                TableColumn::make(__('admin.purchases.columns.created_at')),
                            ])
                            ->schema([
                                TextEntry::make('package_key')
                                    ->label(__('admin.purchases.columns.package'))
                                    ->badge()
                                    ->color('warning')
                                    ->formatStateUsing(fn (string $state): string => __('admin.purchases.packages.'.$state)),

                                TextEntry::make('sessions_total')
                                    ->label(__('admin.purchases.columns.sessions_total')),

                                TextEntry::make('sessions_used')
                                    ->label(__('admin.purchases.columns.sessions_used'))
                                    ->color(fn (int $state): string => $state > 0 ? 'primary' : 'gray'),

                                TextEntry::make('sessions_remaining')
                                    ->label(__('admin.purchases.columns.sessions_remaining'))
                                    ->color(fn (int $state): string => $state === 0 ? 'danger' : 'success'),

                                TextEntry::make('status')
                                    ->label(__('admin.purchases.columns.status'))
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'completed' => 'success',
                                        'pending' => 'warning',
                                        'refunded' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => __('admin.purchases.statuses.'.$state)),

                                TextEntry::make('created_at')
                                    ->label(__('admin.purchases.columns.created_at')),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make(__('admin.learners.sections.upcoming'))
                    ->schema([
                        RepeatableEntry::make('upcoming_sessions')
                            ->label('')
                            ->getStateUsing(fn ($record) => $record->lessonSessions()
                                ->with(['teacherProfile.user', 'availabilitySlot'])
                                ->where('status', 'confirmed')
                                ->whereHas('availabilitySlot', fn ($q) => $q->where('starts_at', '>', now()))
                                ->get()
                                ->sortBy(fn ($s) => $s->availabilitySlot?->starts_at)
                                ->map(fn ($session) => [
                                    'teacher_name' => $session->teacherProfile?->user?->name ?? '—',
                                    'starts_at' => $session->availabilitySlot?->starts_at?->format('d M Y H:i') ?? '—',
                                    'duration' => $session->availabilitySlot
                                        ? $session->availabilitySlot->durationMinutes().' min'
                                        : '—',
                                    'meet_link' => $session->teacherProfile?->meet_link ?? '—',
                                ])
                                ->values()
                                ->all()
                            )
                            ->table([
                                TableColumn::make(__('admin.lesson_sessions.columns.teacher')),
                                TableColumn::make(__('admin.lesson_sessions.columns.starts_at')),
                                TableColumn::make(__('admin.learners.columns.duration')),
                                TableColumn::make(__('admin.learners.columns.meet_link')),
                            ])
                            ->schema([
                                TextEntry::make('teacher_name')
                                    ->label(__('admin.lesson_sessions.columns.teacher')),

                                TextEntry::make('starts_at')
                                    ->label(__('admin.lesson_sessions.columns.starts_at')),

                                TextEntry::make('duration')
                                    ->label(__('admin.learners.columns.duration')),

                                TextEntry::make('meet_link')
                                    ->label(__('admin.learners.columns.meet_link'))
                                    ->placeholder('—'),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make(__('admin.learners.sections.teachers'))
                    ->schema([
                        RepeatableEntry::make('teachers_summary')
                            ->label('')
                            ->getStateUsing(fn ($record) => $record->lessonSessions()
                                ->with(['teacherProfile.user', 'availabilitySlot'])
                                ->where('status', 'confirmed')
                                ->get()
                                ->groupBy('teacher_profile_id')
                                ->map(fn ($sessions) => [
                                    'teacher_name' => $sessions->first()->teacherProfile?->user?->name ?? '—',
                                    'sessions_count' => $sessions->count(),
                                    'last_session' => $sessions
                                        ->filter(fn ($s) => $s->availabilitySlot)
                                        ->sortByDesc(fn ($s) => $s->availabilitySlot->starts_at)
                                        ->first()?->availabilitySlot?->starts_at?->format('d M Y H:i') ?? '—',
                                ])
                                ->sortByDesc('sessions_count')
                                ->values()
                                ->all()
                            )
                            ->table([
                                TableColumn::make(__('admin.lesson_sessions.columns.teacher')),
                                TableColumn::make(__('admin.learners.columns.sessions_count')),
                                TableColumn::make(__('admin.learners.columns.last_session')),
                            ])
                            ->schema([
                                TextEntry::make('teacher_name')
                                    ->label(__('admin.lesson_sessions.columns.teacher')),

                                TextEntry::make('sessions_count')
                                    ->label(__('admin.learners.columns.sessions_count')),

                                TextEntry::make('last_session')
                                    ->label(__('admin.learners.columns.last_session')),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make(__('admin.learners.sections.history'))
                    ->schema([
                        RepeatableEntry::make('session_history')
                            ->label('')
                            ->getStateUsing(fn ($record) => $record->lessonSessions()
                                ->with(['teacherProfile.user', 'availabilitySlot', 'purchase'])
                                ->get()
                                ->sortByDesc(fn ($s) => $s->availabilitySlot?->starts_at)
                                ->map(fn ($session) => [
                                    'teacher_name' => $session->teacherProfile?->user?->name ?? '—',
                                    'starts_at' => $session->availabilitySlot?->starts_at?->format('d M Y H:i') ?? '—',
                                    'duration' => $session->availabilitySlot
                                        ? $session->availabilitySlot->durationMinutes().' min'
                                        : '—',
                                    'status' => $session->status,
                                    'package_key' => $session->purchase?->package_key,
                                ])
                                ->values()
                                ->all()
                            )
                            ->table([
                                TableColumn::make(__('admin.lesson_sessions.columns.teacher')),
                                TableColumn::make(__('admin.lesson_sessions.columns.starts_at')),
                                TableColumn::make(__('admin.learners.columns.duration')),
                                TableColumn::make(__('admin.lesson_sessions.columns.status')),
                                TableColumn::make(__('admin.learners.columns.package')),
                            ])
                            ->schema([
                                TextEntry::make('teacher_name')
                                    ->label(__('admin.lesson_sessions.columns.teacher')),

                                TextEntry::make('starts_at')
                                    ->label(__('admin.lesson_sessions.columns.starts_at')),

                                TextEntry::make('duration')
                                    ->label(__('admin.learners.columns.duration')),

                                TextEntry::make('status')
                                    ->label(__('admin.lesson_sessions.columns.status'))
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'confirmed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => __('admin.lesson_sessions.statuses.'.$state)),

                                TextEntry::make('package_key')
                                    ->label(__('admin.learners.columns.package'))
                                    ->placeholder('—')
                                    ->formatStateUsing(fn (?string $state): string => $state
                                        ? __('admin.purchases.packages.'.$state)
                                        : '—'),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return LearnersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLearners::route('/'),
            'view' => ViewLearner::route('/{record}'),
        ];
    }
}
