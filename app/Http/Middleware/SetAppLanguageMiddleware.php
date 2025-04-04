<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetAppLanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if($request->hasHeader('Language')){
            App()->setLocale($request->header('Language'));
        }
        return $next($request);
    }
}
