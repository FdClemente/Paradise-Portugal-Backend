<?php

namespace App\Filament\Resources;

use App\Filament\Components\Address\GoogleAutocomplete;
use App\Filament\Resources\HouseResource\Pages;
use App\Models\House;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
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
                        Translate::make()
                            ->schema([
                                TextInput::make('name'),
                                RichEditor::make('description')
                                    ->saveUploadedFileAttachmentsUsing(fn($file) => $file->store('houses', 'public')),
                            ])
                            ->locales(config('app.available_locales')),
                        SpatieMediaLibraryFileUpload::make('images')
                            ->collection('house_image')
                            ->preserveFilenames()
                            ->columnSpanFull()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->conversion('webp_format'),
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


                    ]),


                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?House $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?House $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
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

                TextColumn::make('description')
                    ->label(__('filament.house.description'))
                    ->limit()
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
            'index' => Pages\ListHouses::route('/'),
            'create' => Pages\CreateHouse::route('/create'),
            'edit' => Pages\EditHouse::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
