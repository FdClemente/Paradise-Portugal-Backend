<?php

namespace App\Models\House;

use App\Casts\House\PriceCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HousePrices extends Model
{
    protected $fillable = [
        'house_id',
        'date',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => PriceCast::class
    ];

    public function details(): HasOne
    {
        return $this->hasOne(HousePriceDetails::class);
    }
}
