<?php

namespace App\Http\Controllers\Api\House;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\House;

class GetDisableDatesAndPriceController extends Controller
{
    public function __invoke(House $house)
    {
        $disableDates = $house->disableDates()
            ->where('date', '>=', now())
            ->get()
            ->pluck('date')
            ->transform(fn($date)=>$date->format('Y-m-d'))
            ->toArray();

        $customPrices = $house->prices()->where('date', '>=', now())->get()->pluck('price', 'date')->toArray();

        return ApiSuccessResponse::make([
            'disable_dates' => $disableDates,
            'custom_prices' => $customPrices,
            'default_price' => $house->default_price
        ]);
    }
}
