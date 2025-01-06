<?php

namespace App\Models;

use App\Models\Settings\ExperiencePartner;
use App\Models\Settings\ExperienceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Experience extends Model implements HasMedia
{
    use HasTranslations, softDeletes, InteractsWithMedia;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'experience_type_id', 'experience_partner_id', 'name', 'description', 'min_guests', 'registerMediaConversionsUsingModelInstance',
    ];

    public function partner()
    {
        return $this->belongsTo(ExperiencePartner::class);
    }

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
}
