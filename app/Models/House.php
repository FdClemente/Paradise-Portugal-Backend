<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
