<?php

namespace App\Console\Commands\Dev;

use App\Models\Reservation;
use App\Notifications\Reservation\UpcomingReservationNotification;
use Illuminate\Console\Command;

class TestUpcomingReservationCommand extends Command
{
    protected $signature = 'dev:test-upcoming-reservation';

    protected $description = 'Command description';

    public function handle(): void
    {
        $reservation = Reservation::latest()->first();

        $reservation->customer->notify(new UpcomingReservationNotification($reservation));
    }
}
