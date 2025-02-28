<?php

namespace App\Models\House;

use Illuminate\Database\Eloquent\Model;

class ExperienceHouseTravelTime extends Model
{
    protected $fillable = [
        'house_id', 'experience_id', 'travel_time', 'travel_distance',
    ];
}
