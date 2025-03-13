<?php

namespace App\Services\Reservation;

use App\Enum\ReservationStatusEnum;
use App\Models\House\House;
use App\Models\Reservation;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\StripeClient;

class ReservationService
{
    private StripeClient $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * @throws ApiErrorException
     */
    public function cancel(Reservation $reservation): Reservation
    {
        $reservation->status = ReservationStatusEnum::CANCELED_BY_CLIENT;

        $paymentDetails = $this->stripe->paymentIntents->retrieve($reservation->payment_intent);
        //$paymentDetails->cancel();

        $reservation->save();
        $reservation->refresh();
        $reservation->delete();

        return $reservation;
    }

    public function markDates(Reservation $reservation)
    {
        $startDate = $reservation->check_in_date;
        $endDate = $reservation->check_out_date;

        $period = $reservation->house->getDatesRange($startDate, $endDate);

        $datesToInsert = [];

        foreach ($period as $date) {
            $datesToInsert[] = [
                'house_id' => $reservation->house->id,
                'date' => $date->format('Y-m-d'),
                'reason' => 'reserved',
                'reservation_id' => $reservation->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $reservation->house->disableDates()->insert($datesToInsert);
    }

    public function clearDates(Reservation $reservation)
    {
        $reservation->house->disableDates()->delete();
    }

    public function updateDates(Reservation $reservation)
    {
        $this->clearDates($reservation);
        $this->markDates($reservation);
    }
}
