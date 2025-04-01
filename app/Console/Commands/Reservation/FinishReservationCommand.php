<?php

namespace App\Console\Commands\Reservation;

use App\Enum\ReservationStatusEnum;
use App\Models\Reservation;
use Illuminate\Console\Command;

class FinishReservationCommand extends Command
{
    protected $signature = 'reservation:finish';

    protected $description = 'Command description';

    public function handle(): void
    {
        $reservations = Reservation::where('status', ReservationStatusEnum::IN_PROGRESS)
            ->where('check_out_date', '<', now())
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->status = ReservationStatusEnum::COMPLETED;
            $reservation->save();
        }
    }
}
