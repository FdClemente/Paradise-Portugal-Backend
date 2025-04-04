<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\House\House;
use App\Models\Pois\Poi;
use App\Models\WishlistItems;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Meilisearch\Endpoints\Indexes;

class HouseController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $order = $request->get('order', 'price_asc');


        $userId = auth()->id();

        $dateRange = [];
        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $dateRange[] = $currentDate->toDateString();
                $currentDate->addDay();
            }
        }

        $houses = House::search($query, function (Indexes $meilisearch, ?string $query = '', array $options) use ($dateRange, $order) {
            $range = '"' . implode('","', $dateRange) . '"';
            $options['filter'] = "disable_dates NOT IN [" . $range . "]";

            switch ($order) {
                case 'price_asc':
                    $options['sort'] = ['default_price:asc'];
                    break;
                case 'price_desc':
                    $options['sort'] = ['default_price:desc'];
                    break;
                case 'newest':
                    $options['sort'] = ['created_at:desc'];
                    break;
                case 'oldest':
                    $options['sort'] = ['created_at:asc'];
                    break;
            }

            return $meilisearch->search($query, $options);
        })->paginate(25);

        $houseIds = $houses->getCollection()->pluck('id')->toArray();

        if (auth('api')->check()) {
            $userId = auth('api')->id();
            $favorites = WishlistItems::where('wishable_type', House::class)
                ->whereIn('wishable_id', $houseIds)
                ->whereHas('wishlist', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->pluck('wishable_id')
                ->toArray();
        } else {
            $favorites = [];
        }

        if ($order == 'relevance') {
            $houses->getCollection()->transform(function (House $house) use ($dateRange) {
                $house->availability_score = $house->searchScore($dateRange);
                return $house;
            })->sortByDesc('availability_score')->values();
        }

        $houses->getCollection()->transform(function (House $item) use ($favorites) {

            return [
                ...$item->formatToList(),
                'availability_score' => $item->availability_score,
                'isFavorite' => in_array($item->id, $favorites),
            ];
        });


        return ApiSuccessResponse::make([
            'cities' => [],
            'houses' => $houses,
        ]);
    }

    public function show(House $house)
    {
        $houseData = [
            'id' => $house->id,
            'name' => str($house->name)->replace('&amp;', '&')->stripTags()->words(20, ''),
            'description' => \str(html_entity_decode($house->description))->replace('&amp;', '&')->stripTags()->toString(),
            'short_description' => \str($house->description)->replace('&amp;', '&')->stripTags()->words(20),
            'type' => $house->houseType->name,
            'bedrooms' => $house->details?->num_bedrooms,
            'bathrooms' => $house->details?->num_bathrooms,
            'address' => $house->address,
            'guests' => $house->details?->num_guest,
            'min_days_booking' => $house->min_days_booking,
            'isFavorite' => $house->isFavorite(),
            'min_nights' => $house->min_days_booking,
            'image' => $house->getFeaturedImageLink(),
            'house_id' => $house->id,
            'check_in' => $house->details->check_in_time?->format('H:i'),
            'check_out' => $house->details->check_out_time?->format('H:i'),
            'images' => $house->images,
            'default_price' => $house->default_price,
            'ratting' => [
                'airbnb' => $house->airbnb_ratting,
                'booking' => $house->booking_ratting,
            ],
            'features' => $house->features->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'icon' => $item->icon,
                ];
            }),
            'villaDetails' => $house->detailsHighlight->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'icon' => $item->icon,
                ];
            }),
            'location' => [
                'latitude' => $house->latitude,
                'longitude' => $house->longitude,
            ]
        ];

        return ApiSuccessResponse::make([
            'house' => $houseData,
        ]);
    }
}
