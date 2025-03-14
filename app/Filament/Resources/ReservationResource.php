<?php

namespace App\Filament\Resources;

use App\Enum\Experience\TicketPriceType;
use App\Enum\ReservationStatusEnum;
use App\Filament\Resources\CustomerResource\RelationManagers\ReservationsRelationManager;
use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Experiences\ExperiencePrice;
use App\Models\Reservation;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $slug = 'reservations';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament.reservation.reservation_details'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('check_in_date')
                            ->minDate(now()->startOfDay())
                            ->reactive()
                            ->label(__('filament.reservation.check_in_date'))
                            ->required(),

                        DatePicker::make('check_out_date')
                            ->label(__('filament.reservation.check_out_date'))
                            ->reactive()
                            ->minDate(function (Get $get) {
                                return Carbon::parse($get('check_in_date'))->addDay()->startOfDay();
                            })
                            ->required(),

                        Select::make('user_id')
                            ->label(__('filament.reservation.customer'))
                            ->createOptionForm(function (Form $form) {
                                return CustomerResource::form($form);
                            })
                            ->createOptionAction(function (Action $action) {
                                return $action->slideOver();
                            })
                            ->visibleOn('create')
                            ->relationship('customer', 'email')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->required(),

                        Select::make('house_id')
                            ->label(__('filament.reservation.house'))
                            ->relationship('house', 'name'),
                        Select::make('experience_id')
                            ->reactive()
                            ->label(__('filament.reservation.experience'))
                            ->relationship('experience', 'name'),


                        \Filament\Forms\Components\Grid::make()
                            ->columns(3)
                            ->schema([
                                TextInput::make('adults')
                                    ->label(__('filament.reservation.adults'))
                                    ->required()
                                    ->integer(),

                                TextInput::make('children')
                                    ->label(__('filament.reservation.children'))
                                    ->integer(),

                                TextInput::make('babies')
                                    ->label(__('filament.reservation.babies'))
                                    ->integer(),
                            ]),


                        Select::make('status')
                            ->options(ReservationStatusEnum::class)
                            ->label(__('filament.reservation.status'))
                            ->searchable()
                            ->visibleOn('create')
                            ->preload()
                            ->required(),

                        Repeater::make('tickets')
                            ->columnSpanFull()
                            ->visible(fn(Get $get) => $get('experience_id') !== null)
                            ->grid(2)
                            ->relationship('tickets')
                            ->schema([
                                DatePicker::make('date')
                                ->default(function (Get $get) {
                                    return Carbon::parse($get('../../check_in_date'))->startOfDay();
                                })
                                ->minDate(function (Get $get) {
                                    return Carbon::parse($get('../../check_in_date'))->startOfDay();
                                }),
                                TextInput::make('tickets'),
                                Select::make('experience_price_id')
                                    ->relationship('priceDetails', 'price', modifyQueryUsing: function (Builder $query, Get $get) {
                                        $experience = $get('experience_id');
                                        if ($experience) {
                                            $query->where('experience_id', $experience);
                                        }
                                        return $query;
                                    })
                                ->getOptionLabelFromRecordUsing(fn(ExperiencePrice $record) => $record->ticket_type->name)
                            ]),

                        Placeholder::make('created_at')
                            ->label(__('filament.created_at'))
                            ->content(fn(?Reservation $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                        Placeholder::make('updated_at')
                            ->label(__('filament.updated_at'))
                            ->content(fn(?Reservation $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->hiddenOn(ReservationsRelationManager::class)
                    ->label(__('filament.reservation.customer')),

                TextColumn::make('house.name')
                    ->label(__('filament.reservation.house')),
                TextColumn::make('experience.name')
                    ->label(__('filament.reservation.experience')),

                TextColumn::make('check_in_date')
                    ->label(__('filament.reservation.date'))
                    ->tooltip(fn($record) => Carbon::parse($record->check_in_date)->diffInDays(Carbon::parse($record->check_out_date)) . ' ' . __('filament.reservation.days'))
                    ->formatStateUsing(fn($record) => $record->check_in_date->format('d-m-Y') . ' - ' . $record->check_out_date->format('d-m-Y')),

                TextColumn::make('num_guests')
                    ->label(__('filament.reservation.num_guests')),

                TextColumn::make('status')
                    ->badge(fn($record) => $record->status->getColor())
                    ->label(__('filament.reservation.status')),

                TextColumn::make('reservation_code')
                    ->label(__('filament.reservation.reservation_code'))
                    ->copyable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ViewAction::make()->url(fn($record) => ReservationResource::getUrl('view', ['record' => $record->id])),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Grid::make(4)
                ->schema([
                    Grid::make(1)
                        ->columnSpan(3)
                        ->schema([
                            \Filament\Infolists\Components\Section::make(__('filament.reservation.reservation_details'))
                                ->columns(4)
                                ->schema([
                                    TextEntry::make('house.name')
                                        ->label(__('filament.reservation.house'))
                                        ->default(__('filament.reservation.no_house_assigned')),
                                    TextEntry::make('check_in_date')
                                        ->formatStateUsing(fn($record) => $record->check_in_date->format('d-m-Y') . ' - ' . $record->check_out_date->format('d-m-Y'))
                                        ->label(__('filament.reservation.date')),
                                    TextEntry::make('nights')
                                        ->label(__('filament.reservation.nights')),
                                    TextEntry::make('status')
                                        ->badge(fn($record) => $record->status->getColor())
                                        ->formatStateUsing(fn($record) => ucfirst($record->status->value))
                                        ->label(__('filament.reservation.status')),
                                    Fieldset::make(__('filament.reservation.guests'))
                                        ->columns(3)
                                        ->schema([
                                            TextEntry::make('adults')
                                                ->label(__('filament.reservation.adults')),
                                            TextEntry::make('children')
                                                ->label(__('filament.reservation.children')),
                                            TextEntry::make('babies')
                                                ->label(__('filament.reservation.babies')),
                                        ]),
                                ]),
                            \Filament\Infolists\Components\Section::make(__('filament.reservation.experience'))
                                ->columns(1)
                                ->visible(fn($record) => $record->experience_id !== null)
                                ->schema([
                                    TextEntry::make('experience.name')
                                        ->label(__('filament.reservation.experience')),
                                    RepeatableEntry::make('tickets')
                                        ->columns(3)
                                        ->schema([
                                            TextEntry::make('tickets')
                                                ->label(__('filament.reservation.tickets')),
                                            TextEntry::make('date')
                                                ->label(__('filament.reservation.date'))
                                                ->date(),
                                            TextEntry::make('priceDetails.ticket_type')
                                                ->label(__('filament.reservation.ticket_type'))
                                                ->formatStateUsing(fn(TicketPriceType $state) => $state->name),
                                        ])
                                ]),
                        ]),
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            \Filament\Infolists\Components\Section::make(__('filament.reservation.customer_details'))
                                ->columnSpan(1)
                                ->columns(1)
                                ->headerActions([
                                        \Filament\Infolists\Components\Actions\Action::make('view')
                                            ->url(fn($record) => CustomerResource::getUrl('view', ['record' => $record->customer->id]))]
                                )
                                ->schema([
                                    TextEntry::make('customer.name')
                                        ->label(__('filament.reservation.customer_name')),
                                    TextEntry::make('customer.phone_number')
                                        ->label(__('filament.reservation.customer_phone'))
                                        ->copyable(),
                                    TextEntry::make('customer.email')
                                        ->copyable()
                                        ->label(__('filament.reservation.customer_email')),
                                ]),
                            \Filament\Infolists\Components\Section::make(__('filament.reservation.totals'))
                                ->columnSpan(1)
                                ->columns(1)
                                ->schema([
                                    TextEntry::make('houseTotal.total')
                                        ->label(__('filament.reservation.house_total'))
                                        ->visible(fn($record) => $record->house_id !== null)
                                        ->money(currency: 'EUR', divideBy: 100),
                                    TextEntry::make('houseTotal.nightPrice')
                                        ->label(__('filament.reservation.house_total_details'))
                                        ->visible(fn($record) => $record->house_id !== null)
                                        ->formatStateUsing(function (Reservation $record) {
                                            return __('filament.reservation.nights_price', [
                                                'nights' => $record->houseTotal['reservePeriod'],
                                                'nightPrice' => Number::currency($record->houseTotal['nightPrice'] / 100, 'EUR')
                                            ]);
                                        }),
                                    RepeatableEntry::make('tickets')
                                        ->columns(2)
                                        ->schema([
                                            TextEntry::make('priceDetails.ticket_type')
                                                ->label(__('filament.reservation.ticket_type'))
                                                ->formatStateUsing(fn(TicketPriceType $state) => $state->name),
                                            TextEntry::make('ticketPriceDescription')
                                                ->label(__('filament.reservation.ticket_type_short'))
                                        ])

                                ])
                        ])

                ])
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
            'view' => Pages\ViewReservation::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
