<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Actions\TranslateTableAction;
use App\Filament\Resources\Settings\FeatureResource\Pages;
use App\Models\Settings\Feature;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static ?string $slug = 'settings/features';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_group.settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Translate::make()->schema([
                            TextInput::make('name')
                        ]),
                        IconPicker::make('icon')
                            ->sets(['fontawesome-solid', 'heroicons', 'google-material-design-icons']),
                    ]),
                Grid::make(2)
                    ->schema([
                        Placeholder::make('created_at')
                            ->label(__('filament.created_at'))
                            ->content(fn(?Feature $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                        Placeholder::make('updated_at')
                            ->label(__('filament.updated_at'))
                            ->content(fn(?Feature $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                    ])


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('icon')->icon(fn (?Feature $record): string => $record?->icon ?? 'heroicon-o-question-mark-circle'),

            ])
            ->filters([
                //
            ])
            ->actions([
                TranslateTableAction::make(),
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
            'index' => Pages\ListFeatures::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
