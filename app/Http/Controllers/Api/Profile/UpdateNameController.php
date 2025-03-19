<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateNameRequest;
use App\Http\Responses\Api\ApiSuccessResponse;

class UpdateNameController extends Controller
{
    public function __invoke(UpdateNameRequest $request)
    {
        $user = auth()->user();
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->save();
        return ApiSuccessResponse::make();
    }
}
