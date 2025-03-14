<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\ReservationResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservationsRelationManager extends RelationManager
{
    protected static string $relationship = 'reservations';

    protected static ?string $inverseRelationship = 'customer';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return ReservationResource::table($table);
    }
}
