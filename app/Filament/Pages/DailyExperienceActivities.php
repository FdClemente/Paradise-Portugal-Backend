<?php

namespace App\Filament\Pages;

use App\Enum\ReservationStatusEnum;
use App\Models\Reservation;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class DailyExperienceActivities extends Page implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'fas-skiing';

    protected static string $view = 'filament.pages.daily-experience-activities';

    public function table(Table $table): Table
    {
        return $table
            ->query(Reservation::whereNotNull('experience_id')
                ->whereHas('tickets', function ($query) {
                    $query->whereDate('date', now()->format('Y-m-d'));
                }))
            ->columns([
                TextColumn::make('customer.name'),
                TextColumn::make('experience.name'),
                SelectColumn::make('status')
                    ->options([
                        ReservationStatusEnum::NO_SHOW->value => 'No Show',
                        ReservationStatusEnum::COMPLETED->value => 'Completed',
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
