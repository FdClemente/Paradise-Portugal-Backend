<?php

namespace App\Http\Controllers;

use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Pois\Poi;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlist->transform(function ($wishlist) {
            return [
                'id' => $wishlist->id,
                'name' => $wishlist->name,
                'items' => $wishlist->items->transform(function ($item) {
                    return [
                        'type' => $item->wishable_type,
                        'id' => $item->wishable_id,
                        'name' => $item->wishable->name,
                        'image' => $item->wishable->getFirstMediaUrl('default', 'thumb'),
                    ];
                }),
            ];
        });
        return ApiSuccessResponse::make($wishlists);
    }

    public function store(Request $request)
    {
        $response = Wishlist::create([
            'name' => $request->name,
            'user_id' => auth()->user()->id,
        ]);

        return ApiSuccessResponse::make($response);
    }

    public function show(Wishlist $wishlist)
    {
        return ApiSuccessResponse::make($wishlist);
    }

    public function update(Request $request, Wishlist $wishlist)
    {
        $data = $request->validate([
            'name' => ['required'],
        ]);

        $wishlist->update($data);

        return ApiSuccessResponse::make($wishlist);
    }

    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();

        return response()->json();
    }

    public function atatch(Request $request, Wishlist $wishlist)
    {
        $item = $request->all();

        $item = match ($item['type']) {
            'poi' => Poi::find($item['item_id']),
        };


        $wishlist->items()->create([
            'wishable_id' => $item->id,
            'wishable_type' => get_class($item),
        ]);


        return ApiSuccessResponse::make();
    }
}
