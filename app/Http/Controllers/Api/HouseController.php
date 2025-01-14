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
        $selectedCities = $request->get('cities');
        $query = $request->get('q');

        $cities = House::distinct()->get(['city'])->pluck('city');

        if ($selectedCities === null) {
            $selectedCities =  $cities->toArray();
        }else{
            $selectedCities = explode(',', $selectedCities);
        }

        $houses = House::search($query)
            ->whereIn('city', $selectedCities)
            ->paginate(25);

        $houses->getCollection()
            ->transform(function (House $item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => $item->houseType->name,
                    'bedrooms' => $item->details->num_bedrooms,
                    'guests' => $item->details->num_guest,
                    'image' => $item->getFeaturedImageLink(),
                    'images' => $item->images
                ];
            });


        return ApiSuccessResponse::make([
            'cities' => $cities,
            'houses' => $houses,
        ]);
    }
}
