<?php

namespace App\Models\Settings;

use App\Models\Experiences\Experience;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class ExperienceType extends Model implements HasMedia
{
    use SoftDeletes, HasTranslations, InteractsWithMedia;

    protected $fillable = ['name', 'description'];

    public $translatable = ['name', 'description'];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('webp_format')
            ->height(60)
            ->width(60)
            ->format('webp');
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }
}
