<?php

namespace App\Models\Experiences;


use App\Enum\Dates\Months;
use App\Enum\Dates\Weekdays;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperiencesAvailability extends Model
{
    protected $fillable = [
        'experience_id',
        'weekday',
        'month',
        'specific_dates',
        'is_open',
        'is_active',
    ];

    protected $casts = [
        'specific_dates' => 'array',
        'is_open' => 'boolean',
        'is_active' => 'boolean',
        'weekday' => AsEnumCollection::class.':'.Weekdays::class,
        'month' => AsEnumCollection::class.':'.Months::class,
    ];

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    public function specificDatesFormatted(): Attribute
    {
        return Attribute::make(get: function (){
            $dates = $this->specific_dates;
            foreach ($dates as $date){
                $dates = $date;
            }

            return $dates;
        })->shouldCache();
    }

}
