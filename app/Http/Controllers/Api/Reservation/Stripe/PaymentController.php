<?php

namespace App\Http\Controllers\Api\Reservation\Stripe;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Api\Reservation\Trait\HasReservationTotal;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\CalculateTotalRequest;
use App\Http\Responses\Api\ApiErrorResponse;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experiences\ExperiencePrice;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    use HasReservationTotal;
    public function __invoke(CalculateTotalRequest $request)
    {
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            $house = $request->house();
            $experience = $request->experience();

            $checkIn = $request->get('check_in');
            $checkOut = $request->get('check_out');

            if ($house){
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
            }
            $totals = $this->calculateTotals($house, $experience, $request);


            $total = $totals['total'];

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $total,
                'currency' => 'eur',
                'description' => 'Paradise Portugal Reserve',
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

            $reservation = Reservation::create([
                'ip' => $request->ip(),
                'user_id' => \Auth::guard('sanctum')->check()? \Auth::guard('sanctum')->id() : null,
                'status' => ReservationStatusEnum::PENDING_PAYMENT,
                'check_out_date' => Carbon::make($request->get('check_out')),
                'check_in_date' => Carbon::make($request->get('check_in')),
                'adults' => $request->get('adults'),
                'children' => $request->get('children', 0),
                'babies' => $request->get('babies', 0),
                'reservation_code' => $reservationCode,
                'house_id' => $house?->id,
                'experience_id' => $experience?->id,
                'payment_intent' => $paymentIntent->id,
                'payment_intent_secret' => $paymentIntent->client_secret,
                'total_price' => $total,
            ]);

            $reservation->save();

            if ($experience?->id){
                foreach ($request->get('tickets', []) as $date => $tickets) {
                    foreach ($tickets as $ticket) {
                        if ($ticket['tickets'] === 0) continue;

                        $reservation->tickets()->create([
                            'date' => $date,
                            'price' => ExperiencePrice::find($ticket['price_id'])->getRawOriginal('price'),
                            'experience_price_id' => $ticket['price_id'],
                            'tickets' => $ticket['tickets'],
                        ]);
                    }
                }
                $validDates = collect($request->get('tickets', []))
                    ->mapWithKeys(fn($tickets, $date) => [
                        $date => collect($tickets)->sum(fn($ticket) => (int) $ticket['tickets'])
                    ])
                    ->filter(fn($totalTickets) => $totalTickets > 0)
                    ->keys()
                    ->map(fn($date) => Carbon::parse($date))
                    ->sort();

                $minDate = $validDates->first()?->toDateString();
                $maxDate = $validDates->last()?->toDateString();

                $reservation->check_in_date = $minDate;
                $reservation->check_out_date = $maxDate;
                $reservation->save();


            }


            return ApiSuccessResponse::make($paymentDetails);
        }catch (\Exception $e){
            return new ApiErrorResponse($e, $e->getMessage(), 400);
        }


    }
}
