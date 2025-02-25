<?php

namespace App\Models\Pois;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Poi extends Model implements HasMedia
{
    use SoftDeletes, HasTranslations, InteractsWithMedia;

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
}
