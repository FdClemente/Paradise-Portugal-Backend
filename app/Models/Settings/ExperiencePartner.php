<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ExperiencePartner extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'street_name',
        'street_number',
        'city',
        'state',
        'zip',
        'country',
        'latitude',
        'longitude',
        'phone_number',
        'email',
        'website',
        'languages',
    ];

    protected $casts = [
        'languages' => 'array',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('webp_format')
            ->format('webp');
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
}
