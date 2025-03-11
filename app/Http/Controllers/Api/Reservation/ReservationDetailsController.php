<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Api\Reservation\Trait\HasUpcomingDates;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
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
            'date' => $this->formatDate($reservation->check_in_date),
            'isCurrent' => $reservation->check_in_date->isPast(),
            'check_in' => $reservation->check_in_date,
            'check_in_formated' => $reservation->check_in_date->format('D, M j, Y'),
            'check_out' => $reservation->check_out_date,
            'check_out_formated' => $reservation->check_out_date->format('D, M j, Y'),
            'status' => $reservation->status,
            'cancellation_limit' => $reservation->check_in_date->subDays(7)->format('j M.'),
            'house' => $reservation->house ? [
                ...$reservation->house?->formatToList(),
                'address' => $reservation->house?->address_complete,
                'wifi_ssid' => $reservation->house?->details->wifi_ssid,
                'wifi_password' => $reservation->house?->details->wifi_password,
            ] : null,
            'experience' => $reservation->experience ? [
                ...$reservation->experience?->formatToList(),
                'address' => $reservation->experience?->experiencePartner?->address_complete
            ]: null
        ];

        return ApiSuccessResponse::make($data);
    }
}
