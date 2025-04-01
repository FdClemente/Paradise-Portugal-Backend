<?php

namespace App\Http\Controllers\Api\Experiences;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experiences\Experience;
use App\Models\House\ExperienceHouseTravelTime;
use App\Models\House\House;
use App\Models\Settings\ExperienceType;
use App\Models\WishlistItems;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        $experienceTypeIds = $this->getExperienceTypeIds($request);
        $selectedHouse = $this->getSelectedHouse($request);
        $excludedExperienceId = $this->getExcludedExperienceId($request);

        $experienceTypes = ExperienceType::whereIn('id', $experienceTypeIds)
            ->get();

        $experiences = $experienceTypes->flatMap(fn(ExperienceType $type) => $type->experiences->where('id', '<>', $excludedExperienceId));
        $ids = $experiences->pluck('id')->toArray();
        if (auth('api')->check()) {
            $userId = auth('api')->id();
            $favorites = WishlistItems::where('wishable_type', Experience::class)
                ->whereIn('wishable_id', $ids)
                ->whereHas('wishlist', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->pluck('wishable_id')
                ->toArray();
        } else {
            $favorites = [];
        }

        $experiences = $experiences->transform(function (Experience $experience) use ($selectedHouse, $favorites) {
            return $this->transformExperience($experience, $selectedHouse, $favorites);
        });

        return ApiSuccessResponse::make($experiences);
    }

    private function getExperienceTypeIds(Request $request): array
    {
        return $request->has('types')
            ? str($request->get('types'))->explode(',')->toArray()
            : ExperienceType::all()->pluck('id')->toArray();
    }

    private function getSelectedHouse(Request $request): ?House
    {
        return $request->has('house') ? House::find($request->get('house')) : null;
    }

    private function getExcludedExperienceId(Request $request): ?int
    {
        return $request->get('excludeExperience', null);
    }

    private function transformExperience(Experience $experience, ?House $house, array $favorites): array
    {

        if (!$house) {
            return [...$experience->formatToList(), 'isFavorite' => in_array($experience->id, $favorites),];
        }

        $travelTime = $this->getDistanceTimeToPoi($experience, $house);

        return $travelTime->travel_distance && $travelTime->travel_time
            ? [
                ...$experience->formatToList(),
                'isFavorite' => in_array($experience->id, $favorites),
                'travel' => [
                    'distance' => $travelTime->travel_distance,
                    'travel_time' => $travelTime->travel_time,
                ],
            ]
            : [...$experience->formatToList(), 'isFavorite' => in_array($experience->id, $favorites),];
    }

    public function show(Experience $experience)
    {
        $experienceData = [
            'id' => $experience->id,
            'name' => $experience->name,
            'isFavorite' => $experience->isFavorite(),
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
        if ($travelTime) {
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
