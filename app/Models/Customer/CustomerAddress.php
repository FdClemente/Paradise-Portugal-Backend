<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
        'user_id', 'address_line_1', 'address_line_2', 'city', 'postal_code', 'state', 'country',
    ];
}
