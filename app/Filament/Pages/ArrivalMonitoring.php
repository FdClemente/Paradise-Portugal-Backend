<?php

namespace App\Filament\Pages;

use App\Enum\ReservationStatusEnum;
use App\Models\Reservation;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class ArrivalMonitoring extends Page implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'fas-door-open';

    protected static string $view = 'filament.pages.arrival-monitoring';


    public function table(Table $table): Table
    {
        return $table
            ->query(Reservation::where('check_in_date', '<>', now()->format('Y-m-d'))->whereNotNull('house_id'))
            ->columns([
                TextColumn::make('customer.name'),
                TextColumn::make('house.name'),
                SelectColumn::make('status')
                    ->options([
                        ReservationStatusEnum::NO_SHOW->value => 'No Show',
                        ReservationStatusEnum::IN_PROGRESS->value => 'Checked-in',
                        ReservationStatusEnum::CONFIRMED->value => 'Waiting',
                    ])
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
