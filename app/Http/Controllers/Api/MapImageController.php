<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contracts\Interfaces\HasStaticMap;
use App\Models\Experiences\Experience;
use App\Models\House\House;
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
    public function house(House $house)
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
    public function experience(Experience $experience)
    {
        if (!$experience->media()->where('collection_name', 'experience_map_image')->exists()) {
            $googleMapsUrl = $this->generateMapUrl($experience);

            $experience->addMediaFromUrl($googleMapsUrl)->toMediaCollection('experience_map_image');
        }

        $image = $experience->getMedia('experience_map_image')->first()->getPath('webp_format');

        if (!file_exists($image)){
            $image = $experience->getMedia('experience_map_image')->first()->getPath();
        }

        return response()->file($image);
    }

    private function generateMapUrl(HasStaticMap $poi): string
    {
        $baseUrl = 'https://maps.googleapis.com/maps/api/staticmap';
        $poiCoordinates = $poi->latitude . ',' . $poi->longitude;

        $mapSize = '340x216';
        $zoomLevel = 11;
        $apiKey = config('geocoder.key');

        $queryParams = http_build_query([
            'key' => $apiKey,
            'size' => $mapSize,
            'center' => $poiCoordinates,
            'zoom' => $zoomLevel,
            'markers' => $poiCoordinates,
        ]);

        return $baseUrl . '?' . $queryParams;
    }
}
