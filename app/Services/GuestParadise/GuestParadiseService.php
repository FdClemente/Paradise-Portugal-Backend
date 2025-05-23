<?php

namespace App\Services\GuestParadise;

use App\Models\Reservation;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class GuestParadiseService
{
    public function addReservation(Reservation $reservation)
    {
        $data = [
            'accommodation_id' => /*$reservation->house->house_id*/38589,
            'name' => $reservation->customer->first_name.' '.$reservation->customer->last_name,
            'phone' => $reservation->customer->phone_number,
            'email' => $reservation->customer->email,
            'datefrom' => $reservation->check_in_date->addMonths(10)->format('Y-m-d'),
            'dateto' => $reservation->check_out_date->addMonths(10)->format('Y-m-d'),
            'adults' => $reservation->adults ?? 1,
            'children' => $reservation->children ?? 0,
            'babystuff' => $reservation->babies ?? 0,
            'extrabed' => 0,
            'wherereserved' => 'App Paradise'
        ];

        $response = $this->sendRequest('addstay', 'POST', $data);
        $data = $response->json();
        $reservation->external_id = $data['stay_id'];
        $reservation->save();
        return true;
    }

    public function cancelReservation(Reservation $reservation)
    {
        $data = [];

        $response = $this->sendRequest('deletestay/'.$reservation->external_id, 'POST', $data);
        $data = $response->json();
        return true;
    }



    /**
     * @throws ConnectionException
     */
    private function sendRequest($endPoint, $method = 'POST', $data = [])
    {
        $domain = config('services.guestParadise.domain');
        $uri = $domain.'api/'.$endPoint;

        return match ($method) {
            'POST' => Http::withHeaders([
                'Authorization' => 'Bearer '.config('services.guestParadise.auth_token'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($uri, $data),
            default => null
        };

    }
}
