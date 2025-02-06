<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\CalculateTotalRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use Stripe\StripeClient;

class CalculateTotalController extends Controller
{
    public function __invoke(CalculateTotalRequest $request)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $house = $request->house();

        $total = $house->calculateTotalNightsCost($request->get('check_in'), $request->get('check_out'));

        $details = $house->getDetailedPrices($request->get('check_in'), $request->get('check_out'));

        $nightPrice = $house->getRawOriginal('default_price');

        $reservePeriod = $house->getPeriod($request->get('check_in'), $request->get('check_out'));

        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $total,
            'currency' => 'eur',
            'payment_method_types' => ['card', 'paypal'],
        ]);


        $paymentDetails = [
            'payment_intent' => $paymentIntent->client_secret,
        ];

        return ApiSuccessResponse::make([
            'total' => $total,
            'default_price' => $nightPrice,
            'details' => $details,
            'period' => $reservePeriod,
            'payment_details' => $paymentDetails,
        ]);
    }
}
