<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class House extends Model implements HasMedia
{
    use HasTranslations, softDeletes, InteractsWithMedia;

    public $translatable = ['name', 'description'];

    protected $fillable = [
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
}
