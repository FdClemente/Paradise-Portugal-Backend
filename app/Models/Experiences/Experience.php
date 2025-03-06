<?php

namespace App\Models\Experiences;

use App\Casts\House\PriceCast;
use App\Models\Contracts\HasPoi;
use App\Models\Contracts\Interfaces\HasStaticMap;
use App\Models\Settings\ExperiencePartner;
use App\Models\Settings\ExperienceService;
use App\Models\Settings\ExperienceType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Experience extends Model implements HasMedia, HasStaticMap
{
    use HasTranslations, softDeletes, InteractsWithMedia, HasPoi;

    public $translatable = ['name', 'description', 'additional_info'];

    protected $fillable = [
        'experience_type_id',
        'experience_partner_id',
        'name',
        'description',
        'min_guests',
        'adult_price',
        'child_price',
        'additional_info',
        'latitude',
        'longitude',
        ];

    protected $appends = ['latitude', 'longitude'];

    protected $casts = [
        'adult_price' => PriceCast::class,
        'child_price' => PriceCast::class,
    ];

    public function experienceType(): BelongsTo
    {
        return $this->belongsTo(ExperienceType::class);
    }

    public function experiencePartner(): BelongsTo
    {
        return $this->belongsTo(ExperiencePartner::class);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('webp_format')
            ->format('webp');
    }

    public function getFeaturedImageLink(): ?string
    {
        return $this->getFirstMediaUrl(conversionName: 'webp_format');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(ExperienceService::class);
    }

    public function latitude():Attribute
    {
        return Attribute::make(get: fn() => $this->experiencePartner->latitude);
    }

    public function longitude():Attribute
    {
        return Attribute::make(get: fn() => $this->experiencePartner->longitude);
    }

    public function getExtraAttributes():array
    {
        return [
            'images' => $this->images,
            'description' => $this->description,
            'experienceType' => [
                'id' => $this->experienceType->id,
                'name' => $this->experienceType->name,
                'image' => $this->experienceType->getFirstMediaUrl('default', 'thumb'),
            ],
        ];
    }

    public function images(): Attribute
    {
        return Attribute::make(get: function (){
            return $this->getMedia()
                ->transform(fn(Media $media) => $media->getFullUrl('webp_format'))
                ->values()
                ->toArray();
        })->shouldCache();
    }

    public function tickets()
    {
        return $this->hasMany(ExperienceTicket::class);
    }

    public function availability()
    {
        return $this->hasMany(ExperiencesAvailability::class);
    }
}
