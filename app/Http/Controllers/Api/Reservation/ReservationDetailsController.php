<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Api\Reservation\Trait\HasUpcomingDates;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experiences\TicketsReservation;
use App\Models\Reservation;

class ReservationDetailsController extends Controller
{
    use HasUpcomingDates;
    public function __invoke(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->user()->id) {
            abort(403);
        }

        $data = [
            'id' => $reservation->id,
            'total' => $reservation->total_price,
            'date' => $this->formatDate($reservation->check_in_date),
            'isCurrent' => $reservation->check_in_date->isPast(),
            'check_in' => $reservation->check_in_date,
            'check_in_formated' => $reservation->check_in_date->format('D, M j, Y'),
            'check_out' => $reservation->check_out_date,
            'can_show_wifi' => $reservation->check_in_date->isSameDay(now()),
            'check_out_formated' => $reservation->check_out_date->format('D, M j, Y'),
            'status' => $reservation->status,
            'cancellation_limit' => $reservation->check_in_date->subDays(7)->format('j M.'),
            'house' => $reservation->house ? [
                ...$reservation->house?->formatToList(),
                'address' => $reservation->house?->address_complete,
                'wifi_ssid' => $reservation->house?->details->wifi_ssid,
                'wifi_password' => $reservation->house?->details->wifi_password,
                'night_price' => $reservation->house?->getRawOriginal('default_price'),
                'nights' => $reservation->house?->getPeriod($reservation->check_in_date, $reservation->check_out_date),
                'house_total' => $reservation->house?->calculateTotalNightsCost($reservation->check_in_date, $reservation->check_out_date),
            ] : null,
            'experience' => $reservation->experience ? [
                ...$reservation->experience?->formatToList(),
                'address' => $reservation->experience?->experiencePartner?->address_complete,
                'tickets_price' => $reservation->tickets->sum('price'),
                'tickets' => $reservation->tickets->count()
            ]: null,
            'tickets' => $reservation->tickets->map(function (TicketsReservation $ticket){
                return [
                    'date' => $ticket->date->format('D, d M'),
                    'tickets' => $ticket->tickets,
                    'type' => $ticket->priceDetails->ticket_type,
                ];
            }),
        ];

        return ApiSuccessResponse::make($data);
    }
}
