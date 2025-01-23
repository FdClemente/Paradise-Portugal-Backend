<?php

namespace App\Models\House;

use Illuminate\Database\Eloquent\Model;

class HousePriceDetails extends Model
{
    protected $fillable = [
        'house_prices_id',
        'min_days_booking',
        'extra_price_per_guest',
        'period_price_per_weekend',
        'checkin_change_over',
        'checkin_checkout_change_over',
        'price_per_month',
        'price_per_week',
    ];
}
