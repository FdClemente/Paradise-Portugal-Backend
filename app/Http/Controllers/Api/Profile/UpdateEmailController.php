<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateEmailRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use Illuminate\Support\Carbon;

class UpdateEmailController extends Controller
{
    public function __invoke(UpdateEmailRequest $request)
    {
        $user = auth('api')->user();

        $user->email = $request->get('email');
        $user->save();
        return ApiSuccessResponse::make();
    }
}
