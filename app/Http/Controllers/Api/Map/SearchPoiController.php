<?php

namespace App\Http\Controllers\Api\Map;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Pois\Poi;
use App\Models\WishlistItems;
use Illuminate\Http\Request;

class SearchPoiController extends Controller
{
    public function __invoke(Request $request)
    {
        $pois = Poi::search($request->get('q'))->get();
        $ids = $pois->pluck('id')->toArray();

        if(auth('api')->check()){
            $userId = auth('api')->id();
            $favorites = WishlistItems::where('wishable_type', Poi::class)
                ->whereIn('wishable_id', $ids)
                ->whereHas('wishlist', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->pluck('wishable_id')
                ->toArray();
        }else{
            $favorites = [];
        }

        $pois = $pois->transform(function ($poi) use ($favorites, &$house) {
            return[...$poi->formatToMap(),
                'isFavorite' => in_array($poi->id, $favorites)
            ];
        });

        return ApiSuccessResponse::make(['results' => $pois]);
    }
}
