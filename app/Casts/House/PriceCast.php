<?php

namespace App\Casts\House;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PriceCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value/100;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value*100;
    }
}
