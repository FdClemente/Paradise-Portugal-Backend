<?php

namespace App\Http\Resources;

use App\Models\House\House;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin House */
class HouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'street_name' => $this->street_name,
            'street_number' => $this->street_number,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_disabled' => $this->is_disabled,
            'house_id' => $this->house_id,
            'address' => $this->address,
            'address_complete' => $this->address_complete,
            'features_count' => $this->features_count,
        ];
    }
}
