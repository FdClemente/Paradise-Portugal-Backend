<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Enum\ReservationStatusEnum;
use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReservation extends ViewRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->visible(fn(Reservation $record) => in_array($record->status, [ReservationStatusEnum::getActiveReservations()]))

        ];
    }
}
