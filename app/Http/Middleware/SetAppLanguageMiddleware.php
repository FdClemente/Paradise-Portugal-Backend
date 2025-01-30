<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetAppLanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if($request->hasHeader('Accept-Language')){
            App()->setLocale($request->header('Accept-Language'));
        }
        return $next($request);
    }
}
