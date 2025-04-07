<?php

namespace App\Console\Commands\Reservation;

use App\Enum\ReservationStatusEnum;
use App\Models\Reservation;
use App\Notifications\Reservation\UpcomingReservationNotification;
use Illuminate\Console\Command;

class SendReminderCommand extends Command
{
    protected $signature = 'reservation:send-reminder';

    protected $description = 'Command description';

    public function handle(): void
    {
        $date = now()->addDay();
        $reservations = Reservation::where('check_in_date', $date)->where('status', ReservationStatusEnum::CONFIRMED)->get();

        foreach ($reservations as $reservation) {
            $reservation->customer->notify(new UpcomingReservationNotification($reservation));
        }

    }
}
