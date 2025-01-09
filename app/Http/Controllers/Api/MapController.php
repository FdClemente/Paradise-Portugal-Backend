<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MapRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Services\MapService;

class MapController extends Controller
{
    public function __invoke(MapRequest $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $height = $request->get('height', 1280);
        $width = $request->get('width', 720);
        $zoom = $request->get('zoom', 15);
        $query = $request->get('query', '');
        $exclude = $request->get('exclude', []);

        $mapService = app(MapService::class);

        if (!$latitude || !$longitude) {
            return [];
        }

        $coords = convertCoordsToBounds($latitude, $longitude, $zoom, $width, $height);

        [
            'north' => $north,
            'east' => $east,
            'south' => $south,
            'west' => $west
        ] = $coords;

        $zoomRatio = ($zoom - config('map.minZoom')) / (config('map.maxZoom') - config('map.minZoom'));

        $numPoints = intval(config('map.minPoints') + (config('map.maxPoints') - config('map.minPoints')) * $zoomRatio);

        $pois = $mapService->getPoints($north, $east, $south, $west, $latitude, $longitude, $query, $numPoints, $exclude);

        return ApiSuccessResponse::make($pois);
    }
}
