<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;

class CheckNeedShowRatingController extends Controller
{
    public function __invoke()
    {
        $customer = auth('api')->user();

        $reservation = $customer->reservations
            ->where('has_show_ratting_alert', 0)
            ->firstWhere('status', ReservationStatusEnum::COMPLETED);

        if (!$reservation){
            return ApiSuccessResponse::make(['reservation' => null]);
        }

        if ($reservation->house_id){
            return ApiSuccessResponse::make(['reservation' => [
                'type' => 'house',
                'reservation_id' => $reservation->id,
                'image' => $reservation->house->getFeaturedImageLink(),
                'id' => $reservation->house_id,
            ]]);
        }

        if ($reservation->experience_id){
            return ApiSuccessResponse::make(['reservation' => [
                'type' => 'experience',
                'reservation_id' => $reservation->id,
                'image' => $reservation->experience->getFeaturedImageLink(),
                'id' => $reservation->experience_id,
            ]]);
        }
    }
}
