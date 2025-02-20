<?php

namespace App\Http\Controllers\Api\Map;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Pois\TypePoi;

class PoiTypesController extends Controller
{
    public function __invoke()
    {
        $types = \Cache::remember('types_poi', 60,function () {
            return TypePoi::where('is_active', true)
                ->get()
                ->map(fn(TypePoi $type) => [
                    'id' => $type->id,
                    'name' => $type->name,
                    'icon' => $type->icon,
                    'image' => $type->getFirstMediaUrl('default', 'thumb'),
                ])
                ->toArray();
        });



        return ApisuccessResponse::make([
            'types' => $types,
        ]);
    }
}
