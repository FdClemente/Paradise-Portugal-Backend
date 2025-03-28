<?php

namespace App\Models\Pois;

use App\Models\Contracts\HasPoi;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Poi extends Model implements HasMedia
{
    use SoftDeletes, HasTranslations, InteractsWithMedia, HasPoi;

    public $translatable = ['description'];

    protected $fillable = [
        'type_poi_id',
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
        'phone_number',
        'email',
        'website',
    ];
    public function type()
    {
        return $this->belongsTo(TypePoi::class, 'type_poi_id', 'id');
    }

    public function getFeaturedImageLink(): ?string
    {
        return $this->getFirstMediaUrl(conversionName: 'webp_format');
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

    public function getExtraAttributes():array
    {
        return [
            'images' => $this->images,
            'description' => $this->description,
            'typePoi' => [
                'id' => $this->type->id,
                'name' => $this->type->name,
                'icon' => $this->type->icon,
                'image' => $this->type->getFirstMediaUrl('default', 'thumb'),
            ],
        ];
    }

    public function getCustomFilter($options): string
    {
        if (isset($options['selectedTypesIds'])) {
            $types = $options['selectedTypesIds'];
        }else{
            $types = [];
        }

        return 'type_poi_id IN [' . implode(',', $types) . ']';
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('webp_format')
            ->format('webp');
    }

    public function formatToList()
    {
        return [
            ...$this->formatToMap(),
        ];
    }
}
