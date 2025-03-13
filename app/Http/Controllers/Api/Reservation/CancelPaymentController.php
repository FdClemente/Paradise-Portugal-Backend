<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\CancelPaymentRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Reservation;
use App\Services\Reservation\ReservationService;

class CancelPaymentController extends Controller
{
    public function __invoke(CancelPaymentRequest $request)
    {
        $service = new ReservationService();

        $paymentIntent = $request->get('payment_intent');

        $reservation = Reservation::where('payment_intent', $paymentIntent)
            ->where('status', ReservationStatusEnum::PENDING_PAYMENT)
            ->first();
        if (!$reservation) {
            abort(404);
        }
        try {
            $service->cancel($reservation);

            return ApiSuccessResponse::make();
        }catch (\Exception $e){
            return ApiSuccessResponse::make();
        }
    }
}
