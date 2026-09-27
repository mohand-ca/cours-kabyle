<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function getNavigationLabel(): string
    {
        return __('admin.users.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('admin.users.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.users.plural_model_label');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.users.sections.info'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('name')
                                ->label(__('admin.users.columns.name')),

                            TextEntry::make('email')
                                ->label(__('admin.users.columns.email')),

                            TextEntry::make('role')
                                ->label(__('admin.users.columns.role'))
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'admin' => 'danger',
                                    'teacher' => 'info',
                                    'learner' => 'success',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => __('admin.users.roles.'.$state)),

                            TextEntry::make('timezone')
                                ->label(__('admin.users.columns.timezone'))
                                ->placeholder('—'),

                            IconEntry::make('email_verified_at')
                                ->label(__('admin.users.columns.verified'))
                                ->boolean()
                                ->getStateUsing(fn ($record): bool => $record->hasVerifiedEmail()),

                            TextEntry::make('created_at')
                                ->label(__('admin.users.columns.created_at'))
                                ->date('d M Y'),
                        ]),
                    ]),

                Section::make(__('admin.users.sections.learners'))
                    ->schema([
                        RepeatableEntry::make('learners')
                            ->label('')
                            ->table([
                                TableColumn::make(__('admin.learners.columns.name')),
                                TableColumn::make(__('admin.learners.columns.relationship')),
                                TableColumn::make(__('admin.learners.columns.points')),
                                TableColumn::make(__('admin.learners.columns.sessions_count')),
                            ])
                            ->schema([
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

                                TextEntry::make('points')
                                    ->label(__('admin.learners.columns.points')),

                                TextEntry::make('lesson_sessions_count')
                                    ->label(__('admin.learners.columns.sessions_count'))
                                    ->getStateUsing(fn ($record): int => $record->lessonSessions()->count()),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make(__('admin.users.sections.purchases'))
                    ->schema([
                        RepeatableEntry::make('purchases')
                            ->label('')
                            ->table([
                                TableColumn::make(__('admin.purchases.columns.package')),
                                TableColumn::make(__('admin.purchases.columns.sessions_total')),
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
                                    })
                                    ->formatStateUsing(fn (string $state): string => __('admin.purchases.statuses.'.$state)),

                                TextEntry::make('created_at')
                                    ->label(__('admin.purchases.columns.created_at'))
                                    ->date('d M Y'),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'view' => ViewUser::route('/{record}'),
        ];
    }
}
