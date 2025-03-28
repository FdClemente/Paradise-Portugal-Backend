<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\CancellationMotive;

class CancellationMotiveController extends Controller
{
    public function __invoke()
    {
        return ApiSuccessResponse::make(CancellationMotive::all()->transform(function ($motive) {
            return [
                'motive' => $motive->motive,
                'id' => $motive->id,
            ];
        }));
    }
}
