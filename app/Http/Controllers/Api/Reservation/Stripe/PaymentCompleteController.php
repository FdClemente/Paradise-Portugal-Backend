<?php

namespace App\Http\Controllers\Api\Reservation\Stripe;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\PaymentCompleteRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\House;
use App\Models\Reservation;
use App\Services\Customer\CustomerService;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Stripe\StripeClient;

class PaymentCompleteController extends Controller
{
    public function __invoke(PaymentCompleteRequest $request)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $paymentIntent = $this->retrievePaymentIntent($stripe, $request->get('paymentIntent'));

        if ($paymentIntent->status !== "succeeded"){
            abort(402);
        }

        $billingDetails = $this->retrieveBillingDetails($stripe, $paymentIntent->latest_charge);

        $customerService = new CustomerService();

        $customer = $customerService->retrieveCustomer($billingDetails->email);
        $newUser = false;
        if (!$customer){
            $customer = $customerService->createUser($this->fillUserDetails($billingDetails));
            $newUser = true;
        }

        $reservation = Reservation::where('payment_intent', $request->get('paymentIntent'))->firstOrFail();

        $reservation->user_id = $customer->id;
        $reservation->status = ReservationStatusEnum::CONFIRMED;
        $reservation->save();

        $this->markDates($reservation->house, $reservation);

        if ($newUser || !$newUser){
            $userDetails = [
                'email' => $billingDetails->email,
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

    public function fillUserDetails(object $billingDetails)
    {
        $nameParts = explode(' ', $billingDetails->name);
        $phoneParts = $this->splitPhoneNumber($billingDetails->phone);

        return [
            'first_name' => $nameParts[0],
            'last_name' => count($nameParts) > 1 ? $nameParts[count($nameParts) - 1] : '',
            'email' => $billingDetails->email,
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
