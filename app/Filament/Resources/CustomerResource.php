<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use Parfaitementweb\FilamentCountryField\Infolists\Components\CountryEntry;
use Tapp\FilamentCountryCodeField\Forms\Components\CountryCodeSelect;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'customers';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament.user.customer_details'))
                    ->columns()
                    ->schema([
                        TextInput::make('first_name')
                            ->columnSpan(1)
                            ->label(__('filament.user.first_name'))
                            ->required(),
                        TextInput::make('last_name')
                            ->columnSpan(1)
                            ->label(__('filament.user.last_name'))
                            ->required(),
                        TextInput::make('email')
                            ->columnSpan(2)
                            ->label(__('filament.user.email'))
                            ->unique(ignoreRecord: true)
                            ->required(),
                    ]),
                Section::make(__('filament.user.contact_details'))
                    ->columns()
                    ->schema([
                        CountryCodeSelect::make('country_phone')
                            ->required()
                            ->label(__('filament.user.country_code'))
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, Get $get, $state, CountryCodeSelect $component) {
                                if ($state === null)
                                    return;
                                $countryCode = strtoupper($component->getIsoCodeByCountryCode($state));
                                if ($get('country') !== null) {
                                    if ($get('country') !== $countryCode)
                                        return;
                                }
                                $set('country', $countryCode);
                            })
                            ->flags(false),
                        TextInput::make('phone_number')
                            ->label(__('filament.user.phone_number'))
                            ->required(),
                    ]),
                Section::make(__('filament.user.address_details'))
                    ->schema([
                        Country::make('country')
                            ->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('role', 'customer');
                });
            })
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.user.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('filament.user.email'))
                    ->searchable()
                    ->sortable(),

                IconColumn::make('email_verified_at')
                    ->label(__('filament.user.email_verified_at'))
                    ->default(false)
                    ->boolean()
                    ->tooltip(fn(?User $record): string => $record?->email_verified_at?->diffForHumans() ?? __('filament.user.email_verified_placeholder')),
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
            \Filament\Infolists\Components\Section::make(__('filament.user.customer_details'))
                ->columns(4)
                ->schema([
                    TextEntry::make('name')
                        ->label(__('filament.user.name')),
                    TextEntry::make('email')
                        ->copyable()
                        ->iconPosition(IconPosition::Before)
                        ->iconColor('danger')
                        ->icon(fn($record) => $record->email_verified_at ? null : 'heroicon-o-exclamation-circle')
                        ->tooltip(fn($record) => $record->email_verified_at ? null : __('filament.user.email_not_verified'))
                        ->label(__('filament.user.email')),
                    TextEntry::make('phone_number')
                        ->formatStateUsing(fn($record): string => $record->country_phone . ' ' . $record->phone_number)
                        ->copyable()
                        ->copyableState(fn($record): string => $record->country_phone . ' ' . $record->phone_number)
                        ->label(__('filament.user.phone_number')),
                    CountryEntry::make('country')
                    ->label(__('filament.user.country')),
                ])
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPluralLabel(): ?string
    {
        return __('filament.user.customer_plural');
    }

    public static function getLabel(): ?string
    {
        return __('filament.user.customer_singular');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
