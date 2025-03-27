<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\AttachReservationRequest;
use App\Http\Responses\Api\ApiErrorResponse;
use App\Http\Responses\Api\ApiSuccessResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AttachReservationController extends Controller
{
    public function __invoke(AttachReservationRequest $request)
    {
        try {
            $user = auth('api')->user();

            $reservation = $request->reserve();

            if (!$reservation){
                throw new \Exception('Reservation not found');
            }

            $reservation->user_id = $user->id;

            $reservation->save();

            return ApiSuccessResponse::make($reservation);
        }catch (\Exception $e){
            return new ApiErrorResponse($e, 'Reservation not found', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
}
