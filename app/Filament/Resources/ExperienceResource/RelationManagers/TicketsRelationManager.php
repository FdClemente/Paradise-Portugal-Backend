<?php

namespace App\Filament\Resources\ExperienceResource\RelationManagers;

use App\Enum\Experience\TicketPriceType;
use App\Enum\Experience\TicketType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options(TicketType::options()),
                Forms\Components\Repeater::make('prices')
                    ->relationship('prices')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('ticket_type')
                            ->preload()
                            ->options(TicketPriceType::class),
                        Forms\Components\TextInput::make('price'),

                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type')->formatStateUsing(function ($record) {
                    return TicketType::getLabel($record->type);
                }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->slideOver(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->slideOver(),
                Tables\Actions\DeleteAction::make()->disabled(fn (Model $record): bool => $record->prices->count() > 0),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
