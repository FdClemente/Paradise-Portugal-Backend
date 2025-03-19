<?php

namespace App\Models\House;

use Illuminate\Database\Eloquent\Model;

class HouseDetail extends Model
{
    protected $fillable = [
        'house_id',
        'area',
        'num_bedrooms',
        'num_bathrooms',
        'check_in_time',
        'check_out_time',
        'private_bathroom',
        'private_entrance',
        'family_friendly',
        'num_guest',
        'wifi_ssid',
        'wifi_password'
    ];

    protected $casts = [
        'check_in_time' => 'date:H:i:s',
        'check_out_time' => 'date:H:i:s',
        'private_bathroom' => 'boolean',
        'private_entrance' => 'boolean',
        'family_friendly' => 'boolean',
    ];
}
