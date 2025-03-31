<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'user_id',
        'rateable_id',
        'rateable_type',
        'score',
        'comment',
        'status',
    ];
    public function rateable()
    {
        return $this->morphTo();
    }
}
