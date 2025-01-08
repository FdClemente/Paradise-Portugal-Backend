<?php

if (!function_exists('convertCoordsToBounds')) {
    /**
     * Converts coordinates to map bounds.
     *
     * @param  float  $lat  The latitude
     * @param  float  $lng  The longitude
     * @param  int  $zoom  The zoom level
     * @return array The map bounds in the format ['north' => ..., 'east' => ..., 'south' => ..., 'west' => ...]
     */
    function convertCoordsToBounds(float $lat, float $lng, $zoom, int $width, $height): array
    {
        // Convert zoom level to meters per pixel (approximately)
        $metersPerPixel = 156543.03392 * cos(deg2rad($lat)) / pow(2, $zoom + 1.05);

        // Width and height of the visible map in meters (assuming a standard width and height)
        $mapWidthInMeters = $width * $metersPerPixel; // Map width in pixels
        $mapHeightInMeters = $height * $metersPerPixel; // Map height in pixels

        // Convert meters to degrees of latitude and longitude
        $latDelta = $mapHeightInMeters / 111000; // Approximately 111 km per degree of latitude
        $lngDelta = $mapWidthInMeters / (111000 * cos(deg2rad($lat))); // Adjust for latitude

        // Calculate the bounds of the map
        $swLat = formatLatitude($lat - $latDelta);
        $swLng = formatLongitude($lng - $lngDelta);
        $neLat = formatLatitude($lat + $latDelta);
        $neLng = formatLongitude($lng + $lngDelta);

        return [
            'north' => $neLat,
            'east' => $neLng,
            'south' => $swLat,
            'west' => $swLng,
        ];
    }

    function formatLatitude($latitude)
    {
        $latitude = min($latitude, 90);

        return max($latitude, -90);
    }

    function formatLongitude($longitude)
    {
        $longitude = min($longitude, 180);

        return max($longitude, -180);
    }
}
