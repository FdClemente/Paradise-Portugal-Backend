<?php

namespace App\Http\Controllers\Api\Auth\PasswordReset;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;

class CodeCheckController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'email' => 'required|email|exists:users',
            'deviceName' => 'required|string',
        ]);
        $deviceName = $request->deviceName;

        $passwordReset = ResetCodePassword::where('email', $request->email)->firstWhere('code', $request->code);

        if (!$passwordReset) {
            return response(['message' => trans('passwords.code_is_invalid')], 422);
        }

        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        $user = User::firstWhere('email', $request->email);

        $loginService = new LoginService($user, $deviceName);

        $session = $loginService->login();

        return ApiSuccessResponse::make([
            'code' => $passwordReset->code,
            'session' => $session,
            'message' => trans('passwords.code_is_valid')
        ]);
    }
}
