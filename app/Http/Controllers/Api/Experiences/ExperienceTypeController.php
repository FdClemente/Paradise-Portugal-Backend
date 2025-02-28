<?php

namespace App\Http\Controllers\Api\Experiences;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Settings\ExperienceType;

class ExperienceTypeController extends Controller
{
    public function __invoke()
    {
        $experienceTypes = ExperienceType::all();

        $experienceTypes = $experienceTypes->transform(function (ExperienceType $experienceType) {
            return [
                'id' => $experienceType->id,
                'name' => $experienceType->name,
                'image' => $experienceType->getFirstMediaUrl('default', 'thumb'),
            ];
        });

        return ApiSuccessResponse::make($experienceTypes);
    }
}
