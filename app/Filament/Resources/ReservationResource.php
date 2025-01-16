<?php

namespace App\Filament\Resources;

use App\Enum\ReservationStatusEnum;
use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
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
                            ->required(),

                        Select::make('house_id')
                            ->label(__('filament.reservation.house'))
                            ->required()
                            ->relationship('house', 'name'),

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

                        TextInput::make('num_guests')
                            ->label(__('filament.reservation.num_guests'))
                            ->required()
                            ->integer(),

                        Select::make('status')
                            ->options(ReservationStatusEnum::class)
                            ->label(__('filament.reservation.status'))
                            ->searchable()
                            ->visibleOn('create')
                            ->preload()
                            ->required(),

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
                    ->label(__('filament.reservation.customer')),

                TextColumn::make('house.name')
                    ->label(__('filament.reservation.house')),

                TextColumn::make('check_in_date')
                    ->label(__('filament.reservation.date'))
                    ->tooltip(fn($record) => Carbon::parse($record->check_in_date)->diffInDays(Carbon::parse($record->check_out_date)) . ' ' . __('filament.reservation.days'))
                    ->formatStateUsing(fn($record) => $record->check_in_date . ' - ' . $record->check_out_date),

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
                ViewAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Grid::make(4)
                ->schema([
                    \Filament\Infolists\Components\Section::make(__('filament.reservation.reservation_details'))
                        ->columnSpan(3)
                        ->columns(4)
                        ->schema([
                            TextEntry::make('house.name')
                                ->label(__('filament.reservation.house')),
                            TextEntry::make('num_guests')
                                ->label(__('filament.reservation.num_guests')),
                            TextEntry::make('check_in_date')
                                ->formatStateUsing(fn($record) => $record->check_in_date . ' - ' . $record->check_out_date)
                                ->label(__('filament.reservation.date')),
                            TextEntry::make('status')
                                ->badge(fn($record) => $record->status->getColor())
                                ->formatStateUsing(fn($record) => ucfirst($record->status->value))
                                ->label(__('filament.reservation.status')),
                        ]),
                    \Filament\Infolists\Components\Section::make(__('filament.reservation.customer_details'))
                        ->columnSpan(1)
                        ->columns(1)
                        ->headerActions([
                                \Filament\Infolists\Components\Actions\Action::make('view')
                                    ->url(fn($record) => CustomerResource::getUrl('view', ['record' => $record->customer->id]))]
                        )
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('customer.name')
                                ->label(__('filament.reservation.customer_name')),
                            \Filament\Infolists\Components\TextEntry::make('customer.phone_number')
                                ->label(__('filament.reservation.customer_phone'))
                                ->copyable(),
                            \Filament\Infolists\Components\TextEntry::make('customer.email')
                                ->copyable()
                                ->label(__('filament.reservation.customer_email')),
                        ]),
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
