<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Actions\TranslateTableAction;
use App\Filament\Resources\Settings\ExperienceServiceResource\Pages;
use App\Models\Settings\ExperienceService;
use Filament\Forms\Components\Grid;
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
use Guava\FilamentIconPicker\Forms\IconPicker;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class ExperienceServiceResource extends Resource
{
    protected static ?string $model = ExperienceService::class;

    protected static ?string $slug = 'settings/experience-services';

    protected static ?string $navigationGroup = 'Settings';
    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Translate::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name'),
                    ]),

                IconPicker::make('icon')
                    ->sets(['fontawesome-solid', 'heroicons', 'google-material-design-icons']),
                Grid::make()
                    ->columns()
                    ->schema([
                        Placeholder::make('created_at')
                            ->label('Created Date')
                            ->content(fn(?ExperienceService $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                        Placeholder::make('updated_at')
                            ->label('Last Modified Date')
                            ->content(fn(?ExperienceService $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
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
            'index' => Pages\ListExperienceServices::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
