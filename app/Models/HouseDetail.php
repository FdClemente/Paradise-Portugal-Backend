<?php

namespace App\Models;

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
        'num_guest'
    ];
}
