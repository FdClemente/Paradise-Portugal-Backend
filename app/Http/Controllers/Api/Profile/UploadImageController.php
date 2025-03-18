<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UploadImageRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use Illuminate\Http\Request;

class UploadImageController extends Controller
{
    public function __invoke(UploadImageRequest $request)
    {
        $user = auth('api')->user();
        $user->clearMediaCollection('avatar');
        $user->addMediaFromRequest('image')->toMediaCollection('avatar');

        return ApiSuccessResponse::make();
    }
}
