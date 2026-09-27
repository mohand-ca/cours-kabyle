<?php

namespace App\Filament\Resources\Purchases;

use App\Filament\Resources\Purchases\Pages\ListPurchases;
use App\Filament\Resources\Purchases\Pages\ViewPurchase;
use App\Filament\Resources\Purchases\Tables\PurchasesTable;
use App\Models\Purchase;
use BackedEnum;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    public static function getNavigationLabel(): string
    {
        return __('admin.purchases.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('admin.purchases.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.purchases.plural_model_label');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.purchases.sections.details'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('user.name')
                                ->label(__('admin.purchases.columns.user')),

                            TextEntry::make('user.email')
                                ->label(__('admin.users.columns.email')),

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

                            TextEntry::make('sessions_used')
                                ->label(__('admin.purchases.columns.sessions_used'))
                                ->getStateUsing(fn ($record): int => $record->sessions_total - $record->sessions_remaining)
                                ->color(fn (int $state): string => $state > 0 ? 'primary' : 'gray'),

                            TextEntry::make('status')
                                ->label(__('admin.purchases.columns.status'))
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'completed' => 'success',
                                    'pending' => 'warning',
                                    'refunded' => 'danger',
                                })
                                ->formatStateUsing(fn (string $state): string => __('admin.purchases.statuses.'.$state)),

                            TextEntry::make('stripe_session_id')
                                ->label(__('admin.purchases.columns.stripe_session_id'))
                                ->placeholder('—'),

                            TextEntry::make('created_at')
                                ->label(__('admin.purchases.columns.created_at'))
                                ->dateTime('d M Y H:i'),
                        ]),
                    ]),

                Section::make(__('admin.purchases.sections.sessions'))
                    ->schema([
                        RepeatableEntry::make('lessonSessions')
                            ->label('')
                            ->table([
                                TableColumn::make(__('admin.lesson_sessions.columns.learner')),
                                TableColumn::make(__('admin.lesson_sessions.columns.teacher')),
                                TableColumn::make(__('admin.lesson_sessions.columns.starts_at')),
                                TableColumn::make(__('admin.lesson_sessions.columns.status')),
                            ])
                            ->schema([
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
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return PurchasesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchases::route('/'),
            'view' => ViewPurchase::route('/{record}'),
        ];
    }
}
