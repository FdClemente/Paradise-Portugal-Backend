<?php

namespace App\Services;

use App\Models\House;

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

        return $pois->transform(function ($poi) {
            return $poi->formatToMap();
        });
    }
}
