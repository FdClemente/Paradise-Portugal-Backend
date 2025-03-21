<?php

namespace App\Http\Controllers\Api\Reservation\Stripe;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ManageAddress;
use App\Http\Requests\Api\Reservation\PaymentCompleteRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\House\House;
use App\Models\Reservation;
use App\Services\Customer\CustomerService;
use App\Services\Reservation\ReservationService;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Stripe\StripeClient;

class PaymentCompleteController extends Controller
{
    use ManageAddress;
    public function __invoke(PaymentCompleteRequest $request)
    {
        $email = $request->get('email');
        $stripe = new StripeClient(config('services.stripe.secret'));

        $paymentIntent = $this->retrievePaymentIntent($stripe, $request->get('paymentIntent'));

        if ($paymentIntent->status !== "succeeded"){
            abort(402);
        }

        $billingDetails = $this->retrieveBillingDetails($stripe, $paymentIntent->latest_charge);

        $customerService = new CustomerService();

        if(!\Auth::guard('sanctum')->check()){
            $customer = $customerService->createUser($this->fillUserDetails($billingDetails, $email));
            $addressParts = [
                'state' => $billingDetails->address->state,
                'city' => $billingDetails->address->city,
                'postal_code' => $billingDetails->address->postal_code,
                'address_line_1' => $billingDetails->address->line1,
                'address_line_2' => $billingDetails->address->line2,
                'country' => $billingDetails->address->country,
            ];

            $customer->address()->updateOrCreate($this->mapAddress($addressParts));
            $customer->save();
            $newUser = true;
        }else{
            $customer = \Auth::guard('sanctum')->user();
            $newUser = false;
        }

        $reservation = Reservation::where('payment_intent', $request->get('paymentIntent'))->where(function ($query){
            if (\Auth::guard('sanctum')->check())
                $query->where('user_id', \Auth::guard('sanctum')->id());
        })->firstOrFail();

        if ($newUser){
            $reservation->user_id = $customer->id;
        }
        $reservation->status = ReservationStatusEnum::CONFIRMED;
        $reservation->save();

        if ($reservation->house) {
            $reservationService = new ReservationService();
            $reservationService->markDates($reservation);
        }

        if ($newUser || !$newUser){
            $userDetails = [
                'email' => $email,
                'authToken' => $customer->createToken($request->get('deviceName'))->plainTextToken,
            ];
        }else{
            $userDetails = null;
        }

        return ApiSuccessResponse::make([
            'newUser' => $newUser,
            'userDetails' => $userDetails,
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

    public function fillUserDetails(object $billingDetails, $email = null)
    {
        $nameParts = explode(' ', $billingDetails->name);
        $phoneParts = $this->splitPhoneNumber($billingDetails->phone);

        return [
            'first_name' => $nameParts[0],
            'last_name' => count($nameParts) > 1 ? $nameParts[count($nameParts) - 1] : '',
            'email' => $email,
            'email_verified_at' => now(),
            'need_change_password' => true,
            'country_phone' => '+' . $phoneParts['country_code'],
            'phone_number' => $phoneParts['national_number'],
            'phone_number_verified_at' => now(),
            'password' => \Str::random(20),
            'country' => $billingDetails->address->country
        ];
    }

    private function splitPhoneNumber($phoneNumber)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $parsedNumber = $phoneUtil->parse($phoneNumber);

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
}
