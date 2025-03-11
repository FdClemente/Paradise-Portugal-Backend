<?php

namespace App\Services;

use App\Models\House\House;
use App\Models\Settings\PoiHouseTravelTime;

class MapService
{
    public function getPoints($north, $east, $south, $west, $latitude, $longitude, $query, $take, array $exclude = [], $options=[])
    {
        $take = abs($take);
        $models = config('map.models');

        $takePerModel = floor($take / count($models));

        $pois = collect();
        foreach ($models as $model) {
            $model = new $model;
            if ($model->isExcluded($exclude)){
                continue;
            }
            $pois = $pois->merge($model->geoSearchByBox($north, $east, $south, $west, $latitude, $longitude, $query, $options)->take($takePerModel)->get());
        }

        if (!isset($options['house'])){
            return $pois->transform(function ($poi) {
                return $poi->formatToMap();
            });
        }
        $house = House::find($options['house']);
        if (!$house){
            return $pois->transform(function ($poi) use (&$house) {
                return $poi->formatToMap();
            });
        }
        $pois = $pois->transform(function ($poi) use (&$house) {
            $travelTime = $this->getDistanceTime($poi, $house);
            return [
                ...$poi->formatToMap(),
                'travel' => [
                    'distance' => $travelTime->travel_distance,
                    'travel_time' => $travelTime->travel_time,
                ]
            ];
        });

        return $pois;

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
