<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enum\LoginProviders;
use App\Http\Controllers\Auth\InteractsWithOauth;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiErrorResponse;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Auth\LoginProvider;
use App\Models\User;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;

class AppleController extends Controller
{
    use InteractsWithOauth;

    public function __invoke(Request $request)
    {
        try {
            $email = $request->input('email');
            $appleId = $request->input('user');

            if ($email === null) {
                $loginProvider = LoginProvider::where('provider_id', $appleId)->where('provider', LoginProviders::APPLE->value)->first();

                $user = $loginProvider->user;


            }else if (User::where('email', $email)->exists()) {
                $user = $this->findOrValidateLoginProvider($appleId, LoginProviders::APPLE);

            } else {
                $firstName = $request->input('fullName.givenName');
                $lastName = $request->input('fullName.familyName');

                $user = $this->createUserFromSocialiteUser($appleId, $firstName, $lastName, $email, null, LoginProviders::APPLE);

            }

            $deviceName = $request->get('deviceName');
            $loginService = new LoginService($user, $deviceName);

            return ApiSuccessResponse::make($loginService->login());
        } catch (\Exception $e) {
            return new ApiErrorResponse($e);
        }
    }
}
