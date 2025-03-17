<?php

namespace App\Filament\Resources\Pois;

use App\Filament\Components\Address\GoogleAutocomplete;
use App\Filament\Resources\Pois\PoiResource\Pages;
use App\Models\Pois\Poi;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class PoiResource extends Resource
{
    protected static ?string $model = Poi::class;

    protected static ?string $slug = 'pois';

    protected static ?string $navigationIcon = 'fas-map-marker-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(4)
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Section::make(__('filament.poi.poi_details'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('filament.poi.name'))
                                            ->required(),
                                        Select::make('type_poi_id')
                                            ->required()
                                            ->label(__('filament.poi.type'))
                                            ->relationship('type', 'name'),
                                        Translate::make()
                                            ->prefixLocaleLabel()
                                            ->contained(false)
                                            ->columnSpanFull()
                                            ->schema([
                                                TextInput::make('description')
                                                    ->label(__('filament.poi.description')),
                                            ]),
                                        Grid::make(3)
                                            ->columnSpanFull()
                                            ->schema([
                                                TextInput::make('phone_number')
                                                    ->label(__('filament.poi.phone_number')),
                                                TextInput::make('email')
                                                    ->label(__('filament.poi.email')),
                                                TextInput::make('website')
                                                    ->label(__('filament.poi.website')),
                                            ])
                                    ]),
                                Section::make(__('filament.poi.address_details'))
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
                                                    ->label(__('filament.poi.street_name')),
                                                TextInput::make('street_number')
                                                    ->columnSpan(1)
                                                    ->extraInputAttributes([
                                                        'data-google-field' => 'street_number',
                                                    ])
                                                    ->label(__('filament.poi.street_number')),
                                                TextInput::make('zip')
                                                    ->extraInputAttributes([
                                                        'data-google-field' => 'postal_code_prefix',
                                                    ])
                                                    ->label(__('filament.poi.zip_code')),
                                                TextInput::make('city')
                                                    ->label(__('filament.poi.city'))
                                                    ->extraInputAttributes([
                                                        'data-google-field' => 'administrative_area_level_2',
                                                    ]),
                                                TextInput::make('state')
                                                    ->label(__('filament.poi.state'))
                                                    ->extraInputAttributes([
                                                        'data-google-field' => 'administrative_area_level_1',
                                                    ]),
                                                TextInput::make('latitude')
                                                    ->label(__('filament.poi.latitude'))
                                                    ->required(),
                                                TextInput::make('longitude')
                                                    ->label(__('filament.poi.longitude'))
                                                    ->required(),
                                                TextInput::make('country')
                                                    ->label(__('filament.poi.country'))
                                                    ->columnSpanFull()
                                                    ->required(),
                                            ]),
                                    ]),
                            ])->columnSpan(3),
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make(__('filament.house.images'))
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('images')
                                            ->label('')
                                            ->preserveFilenames()
                                            ->columnSpanFull()
                                            ->multiple()
                                            ->reorderable()
                                            ->appendFiles()
                                            ->conversion('webp_format'),
                                    ]),
                            ])
                    ]),


                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Poi $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Poi $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->stacked()
                    ->circular()
                    ->limit(1),
                TextColumn::make('name')->searchable(),
                TextColumn::make('type.name')->searchable(),
                TextColumn::make('city')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListPois::route('/'),
            'create' => Pages\CreatePoi::route('/create'),
            'edit' => Pages\EditPoi::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
