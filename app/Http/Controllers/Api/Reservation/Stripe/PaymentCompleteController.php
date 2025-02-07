<?php

namespace App\Http\Controllers\Api\Reservation\Stripe;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\PaymentCompleteRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\House;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Stripe\StripeClient;

class PaymentCompleteController extends Controller
{
    public function __invoke(PaymentCompleteRequest $request)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $paymentIntent = $this->retrievePaymentIntent($stripe, $request->get('paymentIntent'));
        $billingDetails = $this->retrieveBillingDetails($stripe, $paymentIntent->latest_charge);

        $user = $this->findOrCreateUser($billingDetails);
        $user = $this->updateUserDetails($user, $billingDetails);

        $reservation = Reservation::where('payment_intent', $request->get('paymentIntent'))->first();

        $reservation->user_id = $user->id;
        $reservation->status = ReservationStatusEnum::CONFIRMED;
        $reservation->save();

        $this->markDates($reservation->house, $reservation);

        return ApiSuccessResponse::make([
            'email' => $user->email,
            'message' => 'Reservation confirmed'
        ]);
    }

    private function retrievePaymentIntent(StripeClient $stripe, string $paymentIntentId): object
    {
        return $stripe->paymentIntents->retrieve($paymentIntentId);
    }

    private function retrieveBillingDetails(StripeClient $stripe, string $chargeId): object
    {
        $chargeDetails = $stripe->charges->retrieve($chargeId);
        return $chargeDetails->billing_details;
    }

    private function findOrCreateUser(object $billingDetails): User
    {
        return User::firstOrNew(
            ['email' => $billingDetails->email],
            ['email' => $billingDetails->email]
        );
    }

    private function updateUserDetails(User $user, object $billingDetails): User
    {
        $nameParts = explode(' ', $billingDetails->name);
        $phoneParts = $this->splitPhoneNumber($billingDetails->phone);

        $user->first_name = $nameParts[0];
        $user->last_name = count($nameParts) > 1 ? $nameParts[count($nameParts) - 1] : '';
        $user->email_verified_at = now();
        $user->country_phone = '+'.$phoneParts['country_code'];
        $user->phone_number = $phoneParts['national_number'];
        $user->phone_number_verified_at = now();
        $user->password = \Str::random(20);
        $user->country = $billingDetails->address->country;
        $user->save();

        $user->refresh();

        return $user;
    }

    private function splitPhoneNumber($phoneNumber)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $parsedNumber = $phoneUtil->parse($phoneNumber, null);

            $countryCode = $parsedNumber->getCountryCode();

            $nationalNumber = $parsedNumber->getNationalNumber();

            return [
                'country_code' => $countryCode,
                'national_number' => $nationalNumber
            ];
        } catch (NumberParseException $e) {
            return ['error' => 'invalid phone number: ' . $e->getMessage()];
        }
    }

    private function markDates(House $house, Reservation $reservation)
    {
        $startDate = $reservation->check_in_date;
        $endDate = $reservation->check_out_date;

        $period = $house->getDatesRange($startDate, $endDate);

        $datesToInsert = [];

        foreach ($period as $date) {
            $datesToInsert[] = [
                'house_id' => $house->id,
                'date' => $date->format('Y-m-d'),
                'reason' => 'reserved',
                'reservation_id' => $reservation->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $house->disableDates()->insert($datesToInsert);
    }
}
