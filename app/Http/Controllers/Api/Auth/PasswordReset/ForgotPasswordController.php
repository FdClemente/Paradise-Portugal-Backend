<?php

namespace App\Http\Controllers\Api\Auth\PasswordReset;

use App\Http\Controllers\Controller;
use App\Mail\Auth\SendCodeResetPasswordMail;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Notifications\Auth\ResetCodePasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'email' => [
                'required',
                'email',
                //Rule::exists('users', 'email')->whereNull('deleted_at'),
            ]
        ]);

        ResetCodePassword::where('email', $request->email)->delete();

        $data['code'] = mt_rand(1000, 9999);

        $codeData = ResetCodePassword::create($data);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->notify(new ResetCodePasswordNotification($codeData->code));
        }

        return response(['message' => trans('passwords.sent')], 200);
    }
}
