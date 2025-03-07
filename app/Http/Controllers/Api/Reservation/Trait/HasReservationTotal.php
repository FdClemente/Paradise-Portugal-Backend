<?php

namespace App\Http\Controllers\Api\Reservation\Trait;

use App\Models\Experiences\Experience;
use App\Models\Experiences\ExperiencePrice;
use App\Models\House\House;

trait HasReservationTotal
{
    public function calculateTotals(?House $house, ?Experience $experience, $request)
    {
        $total = 0;

        if ($house){
            $houseDetails = [
                'total' => $house->calculateTotalNightsCost($request->get('check_in'), $request->get('check_out')),
                'details' => $house->getDetailedPrices($request->get('check_in'), $request->get('check_out')),
                'nightPrice' => $house->getRawOriginal('default_price'),
                'reservePeriod' => $house->getPeriod($request->get('check_in'), $request->get('check_out'))
            ];
            $total += $houseDetails['total'];
        }else{
            $houseDetails = [];
        }

        if ($experience){
            $tickets = $request->get('tickets', []);
            $ticketPrices = [];
            $experienceTickets = 0;

            foreach ($tickets as $value) {
                foreach ($value as $ticket){
                    if ($ticket['tickets'] === 0) continue;
                    $experienceTickets += $ticket['tickets'];
                    $price = ExperiencePrice::find($ticket['price_id']);
                    $ticketPrices[] = [
                        'type' => $price->ticket_type,
                        'description' => $experience->name.' x '.$ticket['tickets']." (".$price->ticket_type->value.")",
                        'price' => ($price->getRawOriginal('price') * $ticket['tickets'])
                    ];
                }
            }

            $experienceDetails = [
                'price' => 0,
                'tickets' => $experienceTickets,
                'name' => $experience->name,
            ];

            foreach ($ticketPrices as $ticketPrice){
                $experienceDetails['price'] += $ticketPrice['price'];
                $total += $ticketPrice['price'];
            }
        }else{
            $ticketPrices = [];
            $experienceDetails = [];
        }

        return [
            'total' => $total,
            'houseDetails' => $houseDetails,
            'ticketPrices' => $ticketPrices,
            'experienceDetails' => $experienceDetails
        ];
    }
}
