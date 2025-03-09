<?php

namespace App\Models\Contracts;

use App\Models\WishlistItems;
use Laravel\Scout\Builder;
use Laravel\Scout\Searchable;
use Meilisearch\Endpoints\Indexes;

trait HasPoi
{
    use Searchable;
    private function getCoordinates():array{
        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude,
        ];
    }

    abstract public function getFeaturedImageLink():?string;

    public function toSearchableArray(): array
    {
        $attributes = $this->toArray();

        $coordinates = $this->getCoordinates();

        $extraAttributes = $this->getExtraAttributes();

        $attributes['_geo'] = [
            'lat' => $coordinates['lat'],
            'lng' => $coordinates['lng'],
        ];
        if (method_exists($this, 'getName')) {
            $name = $this->getName();
            $attributes['name'] = $name;
        }
        if (method_exists($this, 'getFeaturedImageLink')) {
            $image = $this->getFeaturedImageLink();
            $attributes['image'] = $image;
        }

        return [
            ...$attributes,
            ...$extraAttributes,
        ];
    }

    public function getExtraAttributes():array
    {
        return [];
    }

    public function geoSearchByBox($north, $east, $south, $west, $lat, $lng, string $query = '', $options): Builder
    {
        if (method_exists($this, 'getCustomFilter')){
            $baseFilter = $this->getCustomFilter($options);
            if ($baseFilter!=""){
                $baseFilter = " AND {$baseFilter}";
            }
        }else{
            $baseFilter = "";
        }

        return $this->search($query, function (Indexes $meilisearch, string $query, array $options) use (
            $north, $east, $south, $west, $lat, $lng, $baseFilter
        ) {
            $options['filter'] = "_geoBoundingBox([{$north}, {$east}], [{$south}, {$west}])";
            $options['filter'] .= $baseFilter;

            $options['sort'] = [
                "_geoPoint({$lat}, {$lng}):asc",
            ];

            $response = $meilisearch->search($query, $options);

            return $response;
        });
    }

    /**
     * @param  $name
     * @param  string  $query
     * @param  bool  $onlyWithPage
     */
    public function geoSearchByPoint($id, $lat, $lng, $query = '', int $distance = 10): Builder
    {
        $distance = $distance * 1000;

        return $this->search($query, function (Indexes $meilisearch, string $query, array $options) use ($id, $lat, $lng, $distance) {
            $options['filter'] = "id != '{$id}'";
            $options['filter'] .= " AND _geoRadius({$lat}, {$lng}, {$distance})";

            $options['sort'] = [
                "_geoPoint({$lat}, {$lng}):asc",
            ];

            return $meilisearch->search($query, $options);
        });
    }

    public function searchByName(string $query = ""): Builder
    {
        return $this->search($query, function (Indexes $meilisearch, string $query, array $options) {
            $options['q'] = $query;

            return $meilisearch->search($query, $options);
        });
    }

    public function formatToMap(): array
    {
        if (method_exists($this, 'getExtraAttributes')){
            $extraAttributes = $this->getExtraAttributes();
        }else{
            $extraAttributes = [];
        }
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'name' => $this->name,
            'image' => $this->getFeaturedImageLink(),
            'id' => $this->getKey(),
            'type' => $this->getClassName(),
            ...$extraAttributes,
        ];
    }

    private function getClassName(): string
    {
        return basename(str_replace('\\', '/', get_class($this)));
    }

    public function isExcluded(array $excluded): bool
    {
        return in_array($this->getClassName(), $excluded);
    }

    public function isFavorite(): bool
    {
        if (!auth('api')->check()) {
            return false;
        }

        return WishlistItems::where('wishable_type', get_class($this))
            ->where('wishable_id', $this->id)
            ->whereHas('wishlist',function ($query){
                return $query->where('user_id', auth('api')->id());
            })
            ->exists();
    }
}
