<?php

namespace App\Http\Controllers\Api\Experiences;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experiences\Experience;
use App\Models\Pois\Poi;
use App\Models\WishlistItems;
use Illuminate\Http\Request;

class SearchExperienceController extends Controller
{
    public function __invoke(Request $request)
    {
        $experiences = Experience::search($request->get('q'))->get();
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

        $experiences = $experiences->transform(function ($experience) use ($favorites, &$house) {
            return [...$experience->formatToMap(),
                'isFavorite' => in_array($experience->id, $favorites)
            ];
        });

        return ApiSuccessResponse::make(['results' => $experiences]);
    }
}
