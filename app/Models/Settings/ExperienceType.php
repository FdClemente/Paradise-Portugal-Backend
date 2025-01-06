<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ExperienceType extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = ['name', 'description'];

    public $translatable = ['name', 'description'];

}
