<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoiHouseTravelTime extends Model
{
    protected $table = 'poi_house_travel_time';

    protected $fillable = [
        'house_id', 'poi_id', 'travel_time', 'travel_distance',
    ];
}
