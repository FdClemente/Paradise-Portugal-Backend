<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\HouseTypeResource\Pages;
use App\Models\Settings\HouseType;
use Filament\Forms\Components\Placeholder;
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

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_group.settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Translate::make()
                    ->prefixLocaleLabel()
                    ->contained(false)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.house_type.name')),
                    ]),
                TextInput::make('wp_category')
                    ->columnSpanFull()
                    ->label(__('filament.house_type.wp_category')),
                Placeholder::make('created_at')
                    ->label(__('filament.created_at'))
                    ->content(fn(?HouseType $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->content(fn(?HouseType $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.house_type.name'))
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
