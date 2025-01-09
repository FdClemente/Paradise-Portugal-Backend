<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\House;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    public function index(Request $request)
    {
        $cities = House::distinct()->get(['city'])->pluck('city');


        return ApiSuccessResponse::make([
            'cities' => $cities,
        ]);
    }
}
