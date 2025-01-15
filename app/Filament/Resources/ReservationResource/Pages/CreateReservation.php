<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Enum\ReservationStatusEnum;
use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        do{
            $reservationCode = rand(10000, 99999);
        }while(Reservation::where('reservation_code', $reservationCode)->whereNotIn('status', [
            ReservationStatusEnum::CANCELED_BY_CLIENT,
            ReservationStatusEnum::CANCELED_BY_OWNER,
            ReservationStatusEnum::COMPLETED,
            ReservationStatusEnum::NO_SHOW
        ])->exists());

        return array_merge($data, [
            'reservation_code' => $reservationCode
        ]);
    }
}
