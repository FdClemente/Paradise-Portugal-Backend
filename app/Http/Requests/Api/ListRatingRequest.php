<?php

namespace App\Http\Requests\Api;

use App\Models\Experiences\Experience;
use App\Models\House\House;
use App\Models\Pois\Poi;
use Illuminate\Foundation\Http\FormRequest;

class ListRatingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|in:house,experience,poi',
            'id' => 'required|integer',
        ];
    }

    public function rateable()
    {
        $model = match ($this->get('type')) {
            'house' => House::findOrFail($this->get('id')),
            'experience' => Experience::findOrFail($this->get('id')),
            'poi' => Poi::findOrFail($this->get('id')),
            default => null,
        };

        if (!$model) {
            abort(404);
        }

        return $model;
    }
}
