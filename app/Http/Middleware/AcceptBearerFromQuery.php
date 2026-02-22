<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * If the request has no Authorization header but has token in query string,
 * set the header so Sanctum can authenticate. Use Headers in production; this is a fallback.
 */
class AcceptBearerFromQuery
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('Authorization')) {
            return $next($request);
        }

        $token = $request->query('token');
        if ($token) {
            $request->headers->set('Authorization', 'Bearer ' . trim($token));
            return $next($request);
        }

        $authQuery = $request->query('Authorization');
        if ($authQuery) {
            $request->headers->set('Authorization', trim($authQuery));
            return $next($request);
        }

        return $next($request);
    }
}
