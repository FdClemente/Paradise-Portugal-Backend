<?php

namespace App\Observers;

use App\Enum\ReservationStatusEnum;
use App\Models\House\House;
use App\Models\Reservation;
use App\Services\GuestParadise\GuestParadiseService;
use App\Services\Reservation\ReservationService;

class ReservationObserver
{
    public function updating(Reservation $reservation): void
    {
        $reservationService = new ReservationService();

        if ($reservation->isDirty('check_in_date', 'check_out_date')) {
            $reservationService->updateDates($reservation);
        }

        if ($reservation->isDirty('house_id')) {
            $originalHouseId = $reservation->getRawOriginal('house_id');
            $currentHouse = $reservation->house;

            if ($originalHouseId) {
                $previousHouse = House::find($originalHouseId);
                if ($previousHouse) {
                    $previousHouse->disableDates()->delete();
                }
            }

            if ($currentHouse) {
                $currentHouse->markDates($reservation);
            }
        }
    }

    public function updated(Reservation $reservation): void
    {
        $reservationService = new ReservationService();

        if ($reservation->isDirty('status')) {
            if ($reservation->status === ReservationStatusEnum::CANCELED_BY_CLIENT || ReservationStatusEnum::CANCELED_BY_OWNER) {
                $reservationService->refund($reservation);

                if ($reservation->house_id != null) {
                    if ($reservation->external_id) {
                        $service = new GuestParadiseService();
                        $service->cancelReservation($reservation);
                    }
                }
            }
            if ($reservation->house_id != null) {
                if ($reservation->status === ReservationStatusEnum::CONFIRMED) {
                    $service = new GuestParadiseService();
                    $service->addReservation($reservation);
                }
            }

        }
    }

    public function deleted(Reservation $reservation): void
    {
        $reservationService = new ReservationService();
        $reservationService->clearDates($reservation);
    }
}
