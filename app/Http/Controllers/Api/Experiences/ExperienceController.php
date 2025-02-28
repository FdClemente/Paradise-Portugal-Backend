<?php

namespace App\Http\Controllers\Api\Experiences;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experience;
use App\Models\House;
use App\Models\Settings\ExperienceType;
use App\Models\Settings\PoiHouseTravelTime;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->has('types')) {
            $types = ExperienceType::all()->pluck('id')->toArray();
        }else{
            $types = str($request->get('types'))->explode(',');
        }

        if ($request->has('house')){
            $house = House::find($request->get('house'));
        }else{
            $house = null;
        }

        $experienceType = ExperienceType::whereIn('id', $types)->get();



        $experiences = $experienceType->flatMap(function (ExperienceType $type) {
            return $type->experiences;
        });
        if (!$house){
            $experiences = $experiences->transform(function (Experience $experience) {
                return $experience->formatToMap();
            });
        }else{
            $experiences = $experiences->transform(function (Experience $experience) use (&$house) {
                $travelTime = $this->getDistanceTimeToPoi($experience, $house);
                return [
                    ...$experience->formatToMap(),
                    'travel' => [
                        'distance' => $travelTime->travel_distance,
                        'travel_time' => $travelTime->travel_time,
                    ]
                ];
            });
        }

        return ApiSuccessResponse::make($experiences);
    }

    public function show($id)
    {

    }

    private function getDistanceTimeToPoi(Experience $experience, House $house)
    {
        $travelTime = House\ExperienceHouseTravelTime::where('house_id', $house->id)->where('experience_id', $experience->id)->first();
        if ($travelTime){
            return $travelTime;
        }

        $apiKey = config('geocoder.key');

        $origin = $house->latitude.','.$house->longitude;

        $destination = $experience->latitude.','.$experience->longitude;

        $url =sprintf("https://maps.googleapis.com/maps/api/distancematrix/json?origins=%s&destinations=%s&key=%s&mode=driving", $origin, $destination, $apiKey);

        $response = file_get_contents($url);

        $response = json_decode($response, true);

        $rows = $response['rows'];
        $distance = $rows[0]['elements'][0]['distance']['text'];
        $travelTime = $rows[0]['elements'][0]['duration']['value'];

        $travelTime = House\ExperienceHouseTravelTime::create([
            'house_id' => $house->id,
            'experience_id' => $experience->id,
            'travel_distance' => $distance,
            'travel_time' => $travelTime,
        ]);

        return $travelTime;
    }
}
