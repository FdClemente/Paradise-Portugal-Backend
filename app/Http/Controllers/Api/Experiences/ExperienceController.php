<?php

namespace App\Http\Controllers\Api\Experiences;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experiences\Experience;
use App\Models\House\ExperienceHouseTravelTime;
use App\Models\House\House;
use App\Models\Settings\ExperienceType;
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

        if ($request->has('excludeExperience')){
            $excludeExperience = $request->get('excludeExperience');
        }else{
            $excludeExperience = null;
        }

        $experienceType = ExperienceType::whereIn('id', $types)
            ->where('id','<>', $excludeExperience)
            ->limit($request->get('limit', 10))
            ->get();



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
                if ($travelTime->travel_distance == null || $travelTime->travel_time == null){
                    return $experience->formatToMap();
                }
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

    public function show(Experience $experience)
    {
        $experienceData = [
            'id' => $experience->id,
            'name' => $experience->name,
            'description' => $experience->description,
            'additional_information' => $experience->additional_info,
            'short_description' => str($experience->description)->stripTags()->words(20),
            'type' => $experience->experienceType->name,
            'images' => $experience->images,
            'services' => $experience->services->transform(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'icon' => $service->icon,
                ];
            }),
            'address' => $experience->experiencePartner->address_complete
        ];

        return ApiSuccessResponse::make([
            'experience' => $experienceData,
        ]);
    }

    private function getDistanceTimeToPoi(Experience $experience, House $house)
    {
        $travelTime = ExperienceHouseTravelTime::where('house_id', $house->id)->where('experience_id', $experience->id)->first();
        if ($travelTime){
            return $travelTime;
        }

        ['distance' => $distance, 'travel_time' => $travelTime] = $house->calculateTravelDistance($experience->latitude, $experience->longitude);


        $travelTime = ExperienceHouseTravelTime::create([
            'house_id' => $house->id,
            'experience_id' => $experience->id,
            'travel_distance' => $distance,
            'travel_time' => $travelTime,
        ]);

        return $travelTime;
    }
}
