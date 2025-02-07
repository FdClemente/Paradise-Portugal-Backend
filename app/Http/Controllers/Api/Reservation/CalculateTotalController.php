<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\CalculateTotalRequest;
use App\Http\Responses\Api\ApiSuccessResponse;

class CalculateTotalController extends Controller
{
    public function __invoke(CalculateTotalRequest $request)
    {

        $house = $request->house();

        $total = $house->calculateTotalNightsCost($request->get('check_in'), $request->get('check_out'));

        $details = $house->getDetailedPrices($request->get('check_in'), $request->get('check_out'));

        $nightPrice = $house->getRawOriginal('default_price');

        $reservePeriod = $house->getPeriod($request->get('check_in'), $request->get('check_out'));

        return ApiSuccessResponse::make([
            'total' => $total,
            'default_price' => $nightPrice,
            'details' => $details,
            'period' => $reservePeriod
        ]);
    }
}
