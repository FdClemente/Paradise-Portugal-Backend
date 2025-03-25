<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\CancelReservationRequest;
use App\Http\Responses\Api\ApiSuccessResponse;

class CancelReservationController extends Controller
{
    public function __invoke(CancelReservationRequest $request)
    {
        $reservation = $request->reservation();

        $reservation->status = ReservationStatusEnum::CUSTOMER_WANT_CANCEL;
        $reservation->cancellation_motive_id = $request->get('motive');

        $reservation->save();

        return ApiSuccessResponse::make();
    }
}
