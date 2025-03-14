<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class HouseDetailsHighlight extends Model
{
    use HasTranslations, SoftDeletes;

    public $translatable = ['name'];

    protected $casts = [
        'show_in_card' => 'boolean'
    ];

    protected $fillable = [
        'name', 'icon', 'show_in_card'
    ];
}
