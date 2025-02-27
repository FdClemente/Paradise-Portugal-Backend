<?php

namespace App\Http\Controllers\Api\Map;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Pois\Poi;
use Illuminate\Http\Request;

class SearchPoiController extends Controller
{
    public function __invoke(Request $request)
    {
        $pois = Poi::search($request->get('q'))->get();

        $pois = $pois->transform(function ($poi) use (&$house) {
            return $poi->formatToMap();
        });

        return ApiSuccessResponse::make(['results' => $pois]);
    }
}
