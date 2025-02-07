<?php

namespace App\Http\Controllers\Api\Reservation\Stripe;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\CalculateTotalRequest;
use App\Http\Responses\Api\ApiErrorResponse;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function __invoke(CalculateTotalRequest $request)
    {
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            $house = $request->house();
            $checkIn = $request->get('check_in');
            $checkOut = $request->get('check_out');

            if (Reservation::where('house_id', $house->id)
                ->whereIn('status', ReservationStatusEnum::getActiveReservations())
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in_date', '<=', $checkIn)
                                ->where('check_out_date', '>=', $checkOut);
                        });
                })->exists()) {
                throw new \Exception('Sorry, this house is already reserved for the selected dates.');
            }


            $total = $house->calculateTotalNightsCost($checkIn, $checkOut);

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $total,
                'currency' => 'eur',
                'payment_method_types' => ['card', 'paypal'],
            ]);

            $paymentDetails = [
                'payment_intent_secret' => $paymentIntent->client_secret,
                'payment_intent' => $paymentIntent->id,
            ];

            do{
                $reservationCode = rand(10000, 99999);
            }while(Reservation::where('reservation_code', $reservationCode)->whereNotIn('status', [
                ReservationStatusEnum::CANCELED_BY_CLIENT,
                ReservationStatusEnum::CANCELED_BY_OWNER,
                ReservationStatusEnum::COMPLETED,
                ReservationStatusEnum::NO_SHOW
            ])->exists());

            Reservation::create([
                'ip' => $request->ip(),
                'user_id' => null,
                'status' => ReservationStatusEnum::PENDING_PAYMENT,
                'check_out_date' => Carbon::make($request->get('check_out')),
                'check_in_date' => Carbon::make($request->get('check_in')),
                'num_guests' => $request->get('num_guests'),
                'reservation_code' => $reservationCode,
                'house_id' => $house->id,
                'payment_intent' => $paymentIntent->id,
                'payment_intent_secret' => $paymentIntent->client_secret,
            ])->save();

            return ApiSuccessResponse::make($paymentDetails);
        }catch (\Exception $e){
            return new ApiErrorResponse($e, $e->getMessage(), 400);
        }


    }
}
