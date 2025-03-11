<?php

namespace App\Models\Contracts;

trait HasTravelDistance
{
    public function calculateTravelDistance($latitude, $longitude)
    {
        $apiKey = config('geocoder.key');

        $origin = $this->latitude.','.$this->longitude;

        $destination = $latitude.','.$longitude;

        $url =sprintf("https://maps.googleapis.com/maps/api/distancematrix/json?origins=%s&destinations=%s&key=%s&mode=driving", $origin, $destination, $apiKey);

        $response = file_get_contents($url);

        $response = json_decode($response, true);

        $rows = $response['rows'];
        if (count($rows) == 0) {
            return [
                'distance' => 0,
                'travel_time' => 0,
            ];
        }

        if ($rows[0]['elements'][0]['status'] != 'OK'){
            return [
                'distance' => 0,
                'travel_time' => 0,
            ];
        }

        $distance = $rows[0]['elements'][0]['distance']['text'];
        $travelTime = $rows[0]['elements'][0]['duration']['value'];

        return [
            'distance' => $distance,
            'travel_time' => $travelTime,
        ];
    }
}
