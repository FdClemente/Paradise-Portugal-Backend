<?php

namespace App\Filament\Resources;

use App\Filament\Actions\TranslateAction;
use App\Filament\Actions\TranslateTableAction;
use App\Filament\Resources\CancellationMotiveResource\Pages;
use App\Models\CancellationMotive;
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

class CancellationMotiveResource extends Resource
{
    protected static ?string $model = CancellationMotive::class;

    protected static ?string $slug = 'cancellation-motives';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Translate::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('motive'),
                    ]),
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?CancellationMotive $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?CancellationMotive $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('motive'),
            ])
            ->filters([
                //
            ])
            ->actions([
                TranslateTableAction::make(),
                EditAction::make()
                    ->slideOver(),
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
            'index' => Pages\ListCancellationMotives::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
