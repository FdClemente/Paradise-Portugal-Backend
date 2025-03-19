<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\User;

class UserController extends Controller
{
    public function __invoke()
    {
        /**
         * @var User $user
         */
        $user = auth()->user();

        return ApiSuccessResponse::make([
            ...$user->toArray(),
            'image' => $user->getFirstMediaUrl('avatar'),
            'address' => $user->address,
            'address_complete' => $user->address_complete
        ]);
    }
}
