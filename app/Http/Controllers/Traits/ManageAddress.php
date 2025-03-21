<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Cache;
use Spatie\Geocoder\Facades\Geocoder;

trait ManageAddress
{
    private function getCoordinates($data)
    {
        $address = $data['addressLine1'].' '.$data['addressLine2'].' '.$data['city'].' '.$data['postalCode'].' '.$data['state'];

        return Cache::remember('geocoder-address-'.$address, now()->addDay(), function () use ($address) {
            return Geocoder::getCoordinatesForAddress($address);
        });
    }

    private function mapAddress($addressData)
    {
        $addressDetails = $this->getCoordinates($addressData);

        $mapped = [
            'address_line_1' => null,
            'address_line_2' => null,
            'city' => null,
            'postal_code' => null,
            'state' => null,
            'country' => null,
        ];

        foreach ($addressDetails['address_components'] as $component) {
            $types = $component->types;

            if (in_array('street_number', $types)) {
                $streetNumber = $component->long_name;
            }
            if (in_array('route', $types)) {
                $streetName = $component->long_name;
            }
            if (in_array('locality', $types)) {
                $mapped['city'] = $component->long_name;
            }
            if (in_array('postal_code', $types)) {
                $mapped['postal_code'] = $component->long_name;
            }
            if (in_array('administrative_area_level_1', $types)) {
                $mapped['state'] = $component->long_name;
            }
            if (in_array('country', $types)) {
                $mapped['country'] = $component->short_name;
            }
        }

        if (!empty($streetNumber) && !empty($streetName)) {
            $mapped['address_line_1'] = "$streetName $streetNumber";
        } elseif (!empty($streetName)) {
            $mapped['address_line_1'] = $streetName;
        }

        return $mapped;
    }
}
