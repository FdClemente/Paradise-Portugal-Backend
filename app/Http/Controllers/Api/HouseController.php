<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class HouseController extends Controller
{
    public function index(Request $request)
    {
        $selectedCities = $request->get('cities');
        $query = $request->get('q');

        $cities = House::distinct()->get(['state'])->pluck('state');

        if ($selectedCities === null) {
            $selectedCities =  $cities->toArray();
        }else{
            $selectedCities = explode(',', $selectedCities);
        }

        $houses = House::search($query)
            ->whereIn('state', $selectedCities)
            ->paginate(25);

        $houses->getCollection()
            ->transform(function (House $item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => $item->houseType->name,
                    'bedrooms' => $item->details?->num_bedrooms,
                    'guests' => $item->details?->num_guest,
                    'image' => $item->getFeaturedImageLink(),
                    'images' => $item->images,
                    'default_price' => $item->default_price,
                ];
            });


        return ApiSuccessResponse::make([
            'cities' => $cities,
            'houses' => $houses,
        ]);
    }

    public function show(House $house){

        $houseData = [
            'id' => $house->id,
            'name' => $house->name,
            'description' => $house->description,
            'short_description' => Str::words(strip_tags($house->description), 20),
            'type' => $house->houseType->name,
            'bedrooms' => $house->details?->num_bedrooms,
            'address' => $house->address,
            'guests' => $house->details?->num_guest,
            'image' => $house->getFeaturedImageLink(),
            'house_id' => $house->id,
            'check_in' => Carbon::createFromFormat('H:i:s',$house->details->check_in_time)->format('H:i'),
            'check_out' => Carbon::createFromFormat('H:i:s',$house->details->check_out_time)->format('H:i'),
            'images' => $house->images,
            'default_price' => $house->default_price,
            'features' => $house->features->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'icon' => $item->icon,
                ];
            }),
            'villaDetails' => $house->detailsHighlight->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'icon' => $item->icon,
                ];
            }),
            'location' => [
                'latitude' => $house->latitude,
                'longitude' => $house->longitude,
            ]
        ];

        return ApiSuccessResponse::make([
            'house' => $houseData,
        ]);
    }
}
