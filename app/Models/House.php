<?php

namespace App\Models;

use App\Models\Contracts\HasPoi;
use App\Models\Settings\Feature;
use App\Models\Settings\HouseType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class House extends Model implements HasMedia
{
    use HasTranslations, softDeletes, InteractsWithMedia, HasPoi;

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
        'wp_id'
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
}
