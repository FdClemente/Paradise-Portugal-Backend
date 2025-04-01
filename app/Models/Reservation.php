<?php

namespace App\Models;

use App\Enum\ReservationStatusEnum;
use App\Models\Experiences\Experience;
use App\Models\Experiences\TicketsReservation;
use App\Models\House\House;
use App\Observers\ReservationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ReservationObserver::class)]
class Reservation extends Model
{
    use SoftDeletes;

    protected $casts = [
        'check_in_date' => 'date',
        'cancellation_date' => 'date',
        'check_out_date' => 'date',
        'has_show_ratting_alert' => 'boolean',
        'experience_rated' => 'boolean',
        'house_rated' => 'boolean',
        'status' => ReservationStatusEnum::class
    ];

    protected $fillable = [
        'user_id',
        'experience_id',
        'house_id',
        'check_in_date',
        'check_out_date',
        'adults',
        'children',
        'cancellation_date',
        'babies',
        'status',
        'reservation_code',
        'payment_intent',
        'cancellation_motive_id',
        'payment_intent_secret',
        'ip',
        'has_show_ratting_alert',
        'house_rated',
        'experience_rated',
        'total_price'
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->whereHas('roles', function ($query) {
            $query->where('role', 'customer');
        });
    }

    public function numGuests(): Attribute
    {
        return Attribute::make(get: function (){
            return $this->children + $this->babies + $this->adults;
        });
    }

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    public function tickets()
    {
        return $this->hasMany(TicketsReservation::class);
    }

    public function cancellation_motive(): BelongsTo
    {
        return $this->belongsTo(CancellationMotive::class);
    }

    public function nights():Attribute
    {
        return Attribute::make(get: function (){
            return $this->check_in_date->diffInDays($this->check_out_date);
        });
    }

    public function houseTotal(): Attribute
    {
        return Attribute::make(get: function (){
            return [
                'total' => $this->house->calculateTotalNightsCost($this->getRawOriginal('check_in_date'), $this->getRawOriginal('check_out_date')),
                'nightPrice' => $this->house->getRawOriginal('default_price'),
                'reservePeriod' => $this->house->getPeriod($this->getRawOriginal('check_in_date'), $this->getRawOriginal('check_out_date'))
            ];
        });
    }

}
