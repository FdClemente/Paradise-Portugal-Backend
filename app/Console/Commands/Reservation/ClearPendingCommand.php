<?php

namespace App\Console\Commands\Reservation;

use App\Enum\ReservationStatusEnum;
use App\Models\Reservation;
use App\Services\Reservation\ReservationService;
use Illuminate\Console\Command;

class ClearPendingCommand extends Command
{
    protected $signature = 'reservation:clear-pending';

    protected $description = 'Command description';

    public function handle(): void
    {
        $reservations = Reservation:://where('status', ReservationStatusEnum::PENDING_PAYMENT)
            where('created_at', '<', now()->subHours(1))
            ->get();

        $service = new ReservationService();

        foreach ($reservations as $reservation) {
            $service->cancel($reservation);
        }
    }
}
