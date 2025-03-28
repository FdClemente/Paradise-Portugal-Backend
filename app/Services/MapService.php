<?php

namespace App\Services;

use App\Models\House\House;
use App\Models\Pois\Poi;
use App\Models\Settings\PoiHouseTravelTime;
use App\Models\WishlistItems;

class MapService
{
    public function getPoints(
        $north,
        $east,
        $south,
        $west,
        $latitude,
        $longitude,
        $query,
        $take,
        array $exclude = [],
        $options = []
    )
    {
        $models = config('map.models');
        $takePerModel = max(1, floor(abs($take) / count($models)));
        $pois = $this->getFilteredPois($models, $north, $east, $south, $west, $latitude, $longitude, $query, $options, $exclude, $takePerModel);

        $favorites = $this->getFavorites($pois);

        if (!isset($options['house'])) {
            return $this->transformPois($pois, $favorites);
        }

        $house = House::find($options['house']);

        if (!$house) {
            return $this->transformPois($pois, $favorites);
        }

        return $this->transformPoisWithTravelTime($pois, $favorites, $house);
    }

    private function getFilteredPois($models, $north, $east, $south, $west, $latitude, $longitude, $query, $options, $exclude, $takePerModel)
    {
        $pois = collect();
        foreach ($models as $modelClass) {
            $model = new $modelClass;
            if ($model->isExcluded($exclude)) {
                continue;
            }
            $pois = $pois->merge(
                $model->geoSearchByBox($north, $east, $south, $west, $latitude, $longitude, $query, $options)
                    ->take($takePerModel)
                    ->get()
            );
        }
        return $pois;
    }

    private function getFavorites($pois): array
    {
        if (!auth('api')->check()) {
            return [];
        }

        $ids = $pois->pluck('id')->toArray();
        return WishlistItems::where('wishable_type', Poi::class)
            ->whereIn('wishable_id', $ids)
            ->whereHas('wishlist', function ($query) {
                $query->where('user_id', auth('api')->id());
            })
            ->pluck('wishable_id')
            ->toArray();
    }

    private function transformPois($pois, $favorites)
    {
        return $pois->transform(function ($poi) use ($favorites) {
            return [
                ...$poi->formatToMap(),
                'isFavorite' => in_array($poi->id, $favorites),
            ];
        });
    }

    private function transformPoisWithTravelTime($pois, $favorites, $house)
    {
        return $pois->transform(function ($poi) use ($favorites, $house) {
            $travelTime = $this->getDistanceTime($poi, $house);
            return [
                ...$poi->formatToMap(),
                'isFavorite' => in_array($poi->id, $favorites),
                'travel' => [
                    'distance' => $travelTime->travel_distance,
                    'travel_time' => $travelTime->travel_time,
                ]
            ];
        });
    }

    private function getDistanceTime($poi, House $house)
    {
        $travelTime = PoiHouseTravelTime::where('house_id', $house->id)->where('poi_id', $poi->id)->first();
        if ($travelTime){
            return $travelTime;
        }

        ['distance' => $distance, 'travel_time' => $travelTime] = $house->calculateTravelDistance($poi->latitude, $poi->longitude);

        $travelTime = PoiHouseTravelTime::create([
            'house_id' => $house->id,
            'poi_id' => $poi->id,
            'travel_distance' => $distance,
            'travel_time' => $travelTime,
        ]);

        return $travelTime;
    }
}
