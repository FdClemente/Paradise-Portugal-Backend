<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Api\Reservation\Trait\HasUpcomingDates;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experiences\TicketsReservation;
use App\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class UpcomingReservationController extends Controller
{
    use HasUpcomingDates;
    public function __invoke()
    {
        $reservations = Reservation::whereIn('status', ReservationStatusEnum::getActiveReservations())
            ->where('user_id', auth('api')->user()->id)
            ->where('check_out_date', '>=', now())
            ->orderBy('check_in_date')
            ->get();

        $reservations = $reservations->transform(function (Reservation $reservation) {
            return [
                'id' => $reservation->id,
                'date' => $this->formatDate($reservation->check_in_date),
                'isCurrent' => $reservation->check_in_date->isPast(),
                'check_in' => $reservation->check_in_date,
                'check_out' => $reservation->check_out_date,
                'status' => $reservation->status,
                'house' => $reservation->house?->formatToList(),
                'experience' => $reservation->experience?->formatToList(),
                'tickets' => $reservation->tickets->map(function (TicketsReservation $ticket){
                    return [
                        'date' => $ticket->date->format('D, d M'),
                        'tickets' => $ticket->tickets,
                        'type' => $ticket->priceDetails->ticket_type,
                    ];
                })
            ];
        });

        return ApiSuccessResponse::make($reservations);
    }


}
