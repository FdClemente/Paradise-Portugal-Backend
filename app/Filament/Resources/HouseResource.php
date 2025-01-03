<?php

namespace App\Filament\Resources;

use App\Filament\Components\Address\GoogleAutocomplete;
use App\Filament\Resources\HouseResource\Pages;
use App\Models\House;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class HouseResource extends Resource
{
    protected static ?string $model = House::class;

    protected static ?string $slug = 'houses';

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament.house.house_details'))
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Select::make('house_type_id')
                                    ->columnSpan(2)
                                    ->label(__('filament.house.house_type'))
                                    ->relationship('houseType', 'name')
                                    ->createOptionForm([
                                        Translate::make()
                                            ->prefixLocaleLabel()
                                            ->contained(false)
                                            ->columnSpanFull()
                                            ->schema([
                                                TextInput::make('name'),
                                            ])
                                            ->locales(config('app.available_locales'))
                                    ]),
                                Select::make('is_disabled')
                                    ->boolean(),
                                TextInput::make('house_id'),
                            ]),
                        Translate::make()
                            ->schema([
                                TextInput::make('name'),
                                RichEditor::make('description')
                                    ->saveUploadedFileAttachmentsUsing(fn($file) => $file->store('houses', 'public')),
                            ])
                            ->locales(config('app.available_locales')),
                    ]),
                Section::make(__('filament.house.address_details'))
                    ->columns(3)
                    ->schema([
                        GoogleAutocomplete::make('address')
                            ->label('Google look-up')
                            ->withFields([
                                TextInput::make('street_name')
                                    ->extraInputAttributes([
                                        'data-google-field' => 'route',
                                    ])
                                    ->columnSpan(2)
                                    ->label(__('filament.house.street_name'))
                                    ->required(),
                                TextInput::make('street_number')
                                    ->columnSpan(1)
                                    ->extraInputAttributes([
                                        'data-google-field' => 'street_number',
                                    ])
                                    ->label(__('filament.house.street_number'))
                                    ->required(),
                                TextInput::make('zip')
                                    ->extraInputAttributes([
                                        'data-google-field' => 'postal_code_prefix',
                                    ])
                                    ->label(__('filament.house.zip_code'))
                                    ->required(),
                                TextInput::make('city')
                                    ->label(__('filament.house.city'))
                                    ->extraInputAttributes([
                                        'data-google-field' => 'administrative_area_level_2',
                                    ])
                                    ->required(),
                                TextInput::make('state')
                                    ->label(__('filament.house.state'))
                                    ->extraInputAttributes([
                                        'data-google-field' => 'administrative_area_level_1',
                                    ])
                                    ->required(),
                                TextInput::make('latitude')
                                    ->label(__('filament.house.latitude'))
                                    ->required(),
                                TextInput::make('longitude')
                                    ->label(__('filament.house.longitude'))
                                    ->required(),
                                TextInput::make('country')
                                    ->label(__('filament.house.country'))
                                    ->columnSpanFull()
                                    ->required(),
                            ]),
                        Grid::make()
                            ->relationship('details')
                            ->schema([
                                TextInput::make('area')
                                    ->label(__('filament.house.area'))
                                    ->suffix('m²'),
                                TextInput::make('num_bedrooms')
                                    ->label(__('filament.house.num_bedrooms'))
                                    ->integer(),
                                TextInput::make('num_bathrooms')
                                    ->label(__('filament.house.num_bathrooms'))
                                    ->integer(),
                                TimePickerField::make('check_in_time')
                                    ->label(__('filament.house.check_in_time')),
                                TimePickerField::make('check_out_time')
                                    ->label(__('filament.house.check_out_time')),
                                Grid::make(3)
                                    ->schema([
                                        Toggle::make('private_bathroom')
                                            ->label(__('filament.house.private_bathroom')),
                                        Toggle::make('private_entrance')
                                            ->label(__('filament.house.private_entrance')),
                                        Toggle::make('family_friendly')
                                            ->label(__('filament.house.family_friendly')),
                                    ])
                            ])
                    ]),
                Section::make(__('filament.house.images'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->collection('house_image')
                            ->label('')
                            ->preserveFilenames()
                            ->columnSpanFull()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->conversion('webp_format'),
                    ]),
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?House $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?House $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Grid::make(3)
            ->schema([
                Infolists\Components\Section::make(__('filament.house.house_details'))
                    ->description(fn(?House $record): string => $record?->name . ' - ' . $record?->address_complete ?? '-')
                ->columnSpan(2),
                Infolists\Components\Section::make(__('filament.house.images'))
                    ->schema([
                        Infolists\Components\ImageEntry::make('images')
                            ->label('')
                            ->width('100%')
                            ->height('100%')
                            ->extraAttributes(['draggable' => 'false'])
                            ->simpleLightbox(fn($record) => $record?->getFirstMediaUrl('house_image'), defaultDisplayUrl: true),
                    ])
                ->columnSpan(1),
            ]),
            Infolists\Components\Section::make(__('filament.house.address_details'))

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->stacked()
                    ->collection('house_image')
                    ->limit(1),
                TextColumn::make('name')
                    ->label(__('filament.house.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('houseType.name')
                    ->label(__('filament.house.house_type'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('filament.house.description'))
                    ->limit(50)
                    ->html(),
                TextColumn::make('street_name')
                    ->label(__('filament.house.street_name')),
                TextColumn::make('street_number')
                    ->label(__('filament.house.street_number'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('city')
                    ->label(__('filament.house.city')),
                TextColumn::make('state')
                    ->label(__('filament.house.state'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('zip')
                    ->label(__('filament.house.zip_code'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('country')
                    ->label(__('filament.house.country'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('latitude')
                    ->label(__('filament.house.latitude'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('longitude')
                    ->label(__('filament.house.longitude'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHouses::route('/'),
            'create' => Pages\CreateHouse::route('/create'),
            'edit' => Pages\EditHouse::route('/{record}/edit'),
            'view' => Pages\ViewHouse::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
