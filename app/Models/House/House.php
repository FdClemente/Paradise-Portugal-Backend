<?php

namespace App\Models\House;

use App\Casts\House\PriceCast;
use App\Models\Contracts\HasPoi;
use App\Models\Contracts\HasReservation;
use App\Models\Contracts\HasTravelDistance;
use App\Models\Contracts\Interfaces\HasStaticMap;
use App\Models\Settings\Feature;
use App\Models\Settings\HouseDetailsHighlight;
use App\Models\Settings\HouseType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class House extends Model implements HasMedia, HasStaticMap
{
    use HasTranslations, softDeletes, InteractsWithMedia, HasPoi, HasReservation, HasTravelDistance;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'is_disabled',
        'house_id',
        'house_type_id',
        'name',
        'description',
        'street_name',
        'street_number',
        'city',
        'state',
        'zip',
        'country',
        'latitude',
        'longitude',
        'wp_id',
        'min_days_booking',
        'booking_ratting',
        'airbnb_ratting',
    ];

    protected $casts = [
        'default_price' => PriceCast::class,
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
            $this
                ->addMediaConversion('webp_format')
                ->format('webp');
    }

    public function houseType(): BelongsTo
    {
        return $this->belongsTo(HouseType::class);
    }

    public function details(): HasOne
    {
        return $this->hasOne(HouseDetail::class, 'house_id', 'id');
    }

    public function disableDates(): HasMany
    {
        return $this->hasMany(HouseDisableDate::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(HousePrices::class)->where('date', '>=', now());
    }
    public function pricesOldest(): HasMany
    {
        return $this->hasMany(HousePrices::class)->orderBy('date', 'desc')->where('date', '<=', now());
    }

    public function address():Attribute
    {
        return Attribute::make(
            get: fn()=>$this->street_name.', '.$this->street_number.' '.$this->city
        );
    }
    public function addressComplete():Attribute
    {
        return Attribute::make(
            get: fn()=>$this->street_name.', '.$this->street_number.' '.$this->zip.' '.$this->city
        );
    }

    public function images(): Attribute
    {
        return Attribute::make(get: function (){
            return $this->getMedia('house_image')
                ->transform(fn(Media $media) => $media->getFullUrl('webp_format'))
                ->values()
                ->toArray();
        })->shouldCache();
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class);
    }

    public function detailsHighlight()
    {
        return $this->belongsToMany(HouseDetailsHighlight::class);
    }

    public function getFeaturedImageLink(): ?string
    {
        return $this->getFirstMediaUrl('house_image',conversionName: 'webp_format');
    }

    private function getName()
    {
        return $this->getTranslations('name');
    }

    public function getExtraAttributes():array
    {
        return [
            'images' => $this->images
        ];
    }

    public function setTranslationForAllLanguages($key, $value)
    {
        $languages = config('app.available_locales');
        foreach ($languages as $language) {
            $this->setTranslation($key, $language, $value);
        }
    }

    public function groupedPrices(): Attribute
    {
        return Attribute::make(function (){
            $prices = $this->prices()->get();

            $groupedPrices = [];
            $currentPeriod = null;

            foreach ($prices as $price) {
                $date = $price->date;
                $currentPrice = $price->price;

                if ($currentPeriod === null || $currentPrice !== $currentPeriod['price'] || $date->diffInDays($currentPeriod['end']) > 1) {
                    if ($currentPeriod !== null) {
                        $groupedPrices[] = $currentPeriod;
                    }
                    $currentPeriod = [
                        'start' => $date,
                        'end' => $date,
                        'price' => $currentPrice,
                        'prices' => [$price]
                    ];
                } else {
                    $currentPeriod['end'] = $date;
                    $currentPeriod['prices'][] = $price;
                }
            }

            if ($currentPeriod !== null) {
                $groupedPrices[] = $currentPeriod;
            }

            return $groupedPrices;

        });
    }

    public function formatToList()
    {
        return [
            'id' => $this->id,
            'name' => str($this->name)->replace('&amp;', '&')->stripTags()->words(20, ''),
            'type' => $this->houseType->name,
            'bedrooms' => $this->details?->num_bedrooms,
            'guests' => $this->details?->num_guest,
            'image' => $this->getFeaturedImageLink(),
            'isFavorite' => $this->isFavorite(),
            'images' => $this->images,
            'features' => $this->detailsHighlight()->where('show_in_card', true)->get()->transform(function ($item) {
                return [
                    'name' => $item->name,
                    'icon' => $item->icon,
                ];
            }),
            'default_price' => $this->default_price,
            'checkInHour' => $this->details?->check_in_time?->format('H:i').' - 21:00',
            'checkOutHour' => $this->details?->check_out_time?->format('H:i').' - 10:30',
            'ratting' => [
                'airbnb' => $this->airbnb_ratting,
                'booking' => $this->booking_ratting,
            ]
        ];
    }
}
