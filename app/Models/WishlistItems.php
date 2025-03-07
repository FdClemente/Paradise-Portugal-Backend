<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistItems extends Model
{
    protected $fillable = [
        'wishlist_id', 'wishable_id', 'wishable_type',
    ];

    public function wishable(){
        return $this->morphTo();
    }
}
