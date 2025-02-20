<?php

namespace App\Filament\Resources\Pois;

use App\Filament\Resources\Pois\TypePoiResource\Pages;
use App\Models\Pois\TypePoi;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class TypePoiResource extends Resource
{
    protected static ?string $model = TypePoi::class;

    protected static ?string $slug = 'pois/type-pois';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Translate::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.typePoi.name')),
                    ])->locales(config('app.available_locales')),
                SpatieMediaLibraryFileUpload::make('images')
                    ->columnSpan(2)
                    ->conversion('thumb'),
                IconPicker::make('icon')
                    ->sets(['heroicons', 'fontawesome-solid']),
                Select::make('is_active')
                    ->boolean()
                    ->default(true),
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?TypePoi $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?TypePoi $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->conversion('thumb')
                    ->circular(),
                TextColumn::make('name'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()->slideOver(),
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
            'index' => Pages\ListTypePois::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
