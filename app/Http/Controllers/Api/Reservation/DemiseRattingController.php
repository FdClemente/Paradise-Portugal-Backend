<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Reservation;

class DemiseRattingController extends Controller
{
    public function __invoke(Reservation $reservation)
    {
        $reservation->has_show_ratting_alert = true;
        $reservation->save();

        return ApiSuccessResponse::make();
    }
}
