<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\House;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class MapImageController extends Controller
{
    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function __invoke(House $house)
    {
        if (!$house->media()->where('collection_name', 'house_map_image')->exists()) {
            $googleMapsUrl = $this->generateMapUrl($house);

            $house->addMediaFromUrl($googleMapsUrl)->toMediaCollection('house_map_image');
        }

        $image = $house->getMedia('house_map_image')->first()->getPath('webp_format');

        if (!file_exists($image)){
            $image = $house->getMedia('house_map_image')->first()->getPath();
        }

        return response()->file($image);
    }

    private function generateMapUrl(House $house): string
    {
        $baseUrl = 'https://maps.googleapis.com/maps/api/staticmap';
        $houseCoordinates = $house->latitude . ',' . $house->longitude;

        $mapSize = '340x216';
        $zoomLevel = 11;
        $apiKey = config('geocoder.key');

        $queryParams = http_build_query([
            'key' => $apiKey,
            'size' => $mapSize,
            'center' => $houseCoordinates,
            'zoom' => $zoomLevel,
            'markers' => $houseCoordinates,
        ]);

        return $baseUrl . '?' . $queryParams;
    }
}
