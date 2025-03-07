<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Api\Reservation\Trait\HasReservationTotal;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\CalculateTotalRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experiences\ExperiencePrice;

class CalculateTotalController extends Controller
{
    use HasReservationTotal;
    public function __invoke(CalculateTotalRequest $request)
    {

        $house = $request->house();

        $experience = $request->experience();


        $totals = $this->calculateTotals($house, $experience, $request);


        return ApiSuccessResponse::make($totals);
    }
}
