<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CancellationMotive extends Model
{
    use HasTranslations;

    public $translatable = ['motive'];


    protected $fillable = [
        'motive',
    ];
}
