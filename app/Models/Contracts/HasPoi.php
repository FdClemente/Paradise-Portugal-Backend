<?php

namespace App\Models\Contracts;

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

    private function getName()
    {
        return $this->name;
    }

    abstract public function getFeaturedImageLink():?string;

    public function toSearchableArray(): array
    {
        $attributes = $this->toArray();

        $coordinates = $this->getCoordinates();
        $name = $this->getName();
        $image = $this->getFeaturedImageLink();

        $attributes['_geo'] = [
            'lat' => $coordinates['lat'],
            'lng' => $coordinates['lng'],
        ];
        $attributes['name'] = $name;
        $attributes['image'] = $image;

        return $attributes;
    }

    public function geoSearchByBox($north, $east, $south, $west, $lat, $lng, string $query = ''): Builder
    {
        return $this->search($query, function (Indexes $meilisearch, string $query, array $options) use (
            $north, $east, $south, $west, $lat, $lng
        ) {
            $options['filter'] = "_geoBoundingBox([{$north}, {$east}], [{$south}, {$west}])";
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
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'name' => $this->name,
            'image' => $this->getFeaturedImageLink(),
            'id' => $this->getKey(),
            'type' => $this->getClassName(),
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
}
