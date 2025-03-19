<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateDateBirthdayRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use Illuminate\Support\Carbon;

class UpdateDateBirthdayController extends Controller
{
    public function __invoke(UpdateDateBirthdayRequest $request)
    {
        $user = auth('api')->user();

        $user->birthday = Carbon::parse($request->get('date_of_birth'));
        $user->save();
        return ApiSuccessResponse::make();
    }
}
