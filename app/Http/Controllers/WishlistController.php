<?php

namespace App\Http\Controllers;

use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experiences\Experience;
use App\Models\House\House;
use App\Models\Pois\Poi;
use App\Models\Wishlist;
use App\Models\WishlistItems;
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
                        'image' => $item->wishable?->getFeaturedImageLink(),
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
        $wishlist->load('items');


        return ApiSuccessResponse::make([
            'id' => $wishlist->id,
            'items' => $wishlist->items->transform(function ($item) {
                return [
                    'wishlist_id' => $item->id,
                    'type' => class_basename($item->wishable_type),
                    ...$item->wishable->formatToList(),
                ];
            })
        ]);
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

    public function attach(Request $request, Wishlist $wishlist)
    {
        $item = $request->all();

        $item = match ($item['type']) {
            'poi' => Poi::find($item['item_id']),
            'house' => House::find($item['item_id']),
            'experience' => Experience::find($item['item_id']),
        };


        $wishlist->items()->create([
            'wishable_id' => $item->id,
            'wishable_type' => get_class($item),
        ]);


        return ApiSuccessResponse::make();
    }

    public function detach(Request $request)
    {
        $item = $request->all();

        $item = match ($item['type']) {
            'poi' => Poi::find($item['item_id']),
            'house' => House::find($item['item_id']),
            'experience' => Experience::find($item['item_id']),
        };

        $wishlistItems = WishlistItems::where('wishable_id', $item->id)
            ->where('wishable_type', get_class($item))
            ->get();

        foreach ($wishlistItems as $wishlistItem){

            if ($wishlistItem->wishlist->items->count() === 1){
                $wishlistItem->wishlist->delete();
            }
            $wishlistItem->delete();
        }

        return ApiSuccessResponse::make();
    }
}
