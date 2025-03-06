<?php

namespace App\Filament\Resources\ExperienceResource\RelationManagers;

use App\Enum\Dates\Months;
use App\Enum\Dates\Weekdays;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AvailabilityRelationManager extends RelationManager
{
    protected static string $relationship = 'availability';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('weekday')
                    ->multiple()
                    ->autofocus()
                    ->options(Weekdays::class)
                    ->nullable(),

                Forms\Components\Select::make('month')
                    ->multiple()
                    ->options(Months::class)
                    ->nullable(),

                Forms\Components\Repeater::make('specific_dates')
                    ->grid(2)
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label('Data')
                            ->minDate(now())
                            ->required()
                    ])
                    ->columnSpanFull()
                    ->columns(1),

                Forms\Components\Toggle::make('is_open')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('month')
            ->columns([
                Tables\Columns\TextColumn::make('month')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return $state->label();
                    }),
                Tables\Columns\TextColumn::make('weekday')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return $state->label();
                    }),
                Tables\Columns\TextColumn::make('specific_dates_formatted')
                    ->date()
                    ->badge(),
                Tables\Columns\IconColumn::make('is_open')
                    ->boolean()


            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->slideOver(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->slideOver(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
