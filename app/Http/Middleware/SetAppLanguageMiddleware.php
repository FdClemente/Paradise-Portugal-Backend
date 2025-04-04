<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetAppLanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if($request->hasHeader('Language')){
            $language = $request->header('Language');
            if (strtolower($language) == 'pt'){
                $language = 'pt-PT';
            }else{
                $language = strtolower($language);
            }
            App()->setLocale($language);
        }
        return $next($request);
    }
}
