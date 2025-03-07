<?php

namespace App\Models;

use App\Models\House\House;
use App\Models\Pois\Poi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'name',
    ];


    public function pois(): MorphToMany
    {
        return $this->morphToMany(Poi::class, 'wishable', 'wishlist_items');
    }

    public function houses(): MorphToMany
    {
        return $this->morphToMany(House::class, 'wishable', 'wishlist_items');
    }

    public function items(): HasMany
    {
        return $this->hasMany(WishlistItems::class);
    }

    public function allItems()
    {
        return collect()
            ->merge($this->pois)
            ->merge($this->houses);
    }
}
