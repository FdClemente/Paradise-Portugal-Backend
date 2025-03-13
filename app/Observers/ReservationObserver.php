<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Services\Reservation\ReservationService;

class ReservationObserver
{
    public function updating(Reservation $reservation): void
    {
        $reservationService = new ReservationService();

        if ($reservation->isDirty('check_in_date', 'check_out_date')){
            $reservationService->updateDates($reservation);
        }

        if ($reservation->isDirty('house_id')){
            $reservationService->clearDates($reservation);
        }
    }

    public function deleted(Reservation $reservation): void
    {
        $reservationService = new ReservationService();
        $reservationService->clearDates($reservation);
    }
}
