<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ExperienceService extends Model
{
    use HasTranslations, SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        'name', 'icon',
    ];
}
