<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ETagMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isMethod('get') && !$request->isMethod('head')) {
            return $next($request);
        }

        $initialMethod = $request->method();

        $request->setMethod('get');

        /** @var Response $response */
        $response = $next($request);

        $etag = md5(json_encode($response->headers->get('origin')) . (string)$response->getContent());

        $requestEtag = str_replace('"', '', $request->getETags());

        if ($requestEtag && $requestEtag[0] == $etag && !$request->isNoCache()) {
            $response->setNotModified();
        }

        $response->setEtag($etag);

        $request->setMethod($initialMethod);

        return $response;
    }
}
