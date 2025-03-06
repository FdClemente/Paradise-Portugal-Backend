<?php

namespace App\Models\House;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;

class HouseDisableDate extends Model
{
    protected $casts = [
        'date' => 'date'
    ];

    protected $fillable = [
        'date',
        'house_id',
        'reason',
        'reservation_id'
    ];

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function reservation()
    {
        return $this->hasOne(Reservation::class);
    }
}
