<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
            throw new AuthorizationException();
        }

        if ($user->hasVerifiedEmail()) {
            Auth::loginUsingId($user->id, $remember = true);
            return view('auth.verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        Auth::loginUsingId($user->id, $remember = true);

        return view('auth.verified');
    }
}
