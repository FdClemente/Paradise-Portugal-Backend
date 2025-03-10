<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class UpcomingReservationController extends Controller
{
    public function __invoke()
    {
        $reservations = Reservation::whereIn('status', ReservationStatusEnum::getActiveReservations())
            ->where('user_id', auth('api')->user()->id)
            ->orderBy('check_in_date')
            ->get();

        $reservations = $reservations->transform(function (Reservation $reservation) {
            return [
                'id' => $reservation->id,
                'date' => $reservation->check_in_date?->diffForHumans(now(), ['parts' => 1, 'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,     'options' => CarbonInterface::JUST_NOW | CarbonInterface::ONE_DAY_WORDS | CarbonInterface::TWO_DAY_WORDS]),
                'check_in' => $reservation->check_in_date,
                'check_out' => $reservation->check_out_date,
                'status' => $reservation->status,
                'house' => $reservation->house?->formatToList(),
                'experience' => $reservation->experience?->formatToList()
            ];
        });

        return ApiSuccessResponse::make($reservations);
    }
}
