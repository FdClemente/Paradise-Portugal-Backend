<?php

namespace App\Models\Pois;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class TypePoi extends Model implements HasMedia
{
    use InteractsWithMedia, HasTranslations;

    protected $table = 'types_pois';
    public $translatable = ['name'];

    protected $fillable = ['name', 'icon', 'is_active'];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('webp_format')
            ->height(60)
            ->width(60)
            ->format('webp');
    }
}
