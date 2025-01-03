<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\HouseTypeResource\Pages;
use App\Models\HouseType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class HouseTypeResource extends Resource
{
    protected static ?string $model = HouseType::class;

    protected static ?string $slug = 'settings/house-types';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Translate::make()
                    ->prefixLocaleLabel()
                    ->contained(false)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name'),
                    ])
                    ->locales(config('app.available_locales')),
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?HouseType $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?HouseType $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListHouseTypes::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
