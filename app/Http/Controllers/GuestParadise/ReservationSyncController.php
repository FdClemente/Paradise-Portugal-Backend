<?php

namespace App\Http\Controllers\GuestParadise;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Guest\ReservationRequest;
use App\Http\Responses\Api\ApiErrorResponse;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\House\House;
use App\Models\Reservation;
use App\Services\Reservation\ReservationService;

class ReservationSyncController extends Controller
{
    public function store(ReservationRequest $request)
    {
        $user = auth()->user();

        if (!$user->tokenCan('server:integration')){
            abort(400);
        }

        if (House::where('wp_id', $request->get('house_id'))->first() == null){
            abort(404);
        }

        try {
            do{
                $reservationCode = rand(10000, 99999);
            }while(Reservation::where('reservation_code', $reservationCode)->whereNotIn('status', [
                ReservationStatusEnum::CANCELED_BY_CLIENT,
                ReservationStatusEnum::CANCELED_BY_OWNER,
                ReservationStatusEnum::COMPLETED,
                ReservationStatusEnum::NO_SHOW
            ])->exists());

            $reservation = new Reservation();
            $reservation->external_id = $request->get('id');
            $reservation->house_id = House::where('wp_id', $request->get('house_id'))->first()->id;
            $reservation->check_in_date = $request->get('check_in_date');
            $reservation->check_out_date = $request->get('check_out_date');
            $reservation->adults = $request->get('adults');
            $reservation->children = $request->get('children');
            $reservation->status = ReservationStatusEnum::CONFIRMED;
            $reservation->reservation_code = $reservationCode;
            $reservation->save();

            if ($reservation->house) {
                $reservationService = new ReservationService();
                $reservationService->markDates($reservation);
            }

            return ApiSuccessResponse::make();
        }catch (\Exception $exception){
            return new ApiErrorResponse($exception);
        }

    }

    public function destroy(string $externalId)
    {
        $user = auth()->user();

        if (!$user->tokenCan('server:integration')){
            abort(400);
        }


        $reservation = Reservation::where('external_id', $externalId)->first();
        $reservation?->delete();

        return ApiSuccessResponse::make();
    }
}
