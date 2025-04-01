<?php

namespace App\Console\Commands\Reservation;

use App\Enum\ReservationStatusEnum;
use App\Models\Reservation;
use Illuminate\Console\Command;

class SetReservationAsInProgressCommand extends Command
{
    protected $signature = 'reservation:start';

    protected $description = 'Start period of reservation';

    public function handle(): void
    {
        $reservations = Reservation::where('status', ReservationStatusEnum::CONFIRMED)
            ->where('check_in_date', '<', now())
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->status = ReservationStatusEnum::IN_PROGRESS;
            $reservation->save();
        }
    }
}
