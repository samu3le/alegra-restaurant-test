<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


/**
 * Data parser, verify token and put ipAddress and userAgent to the request
 * @param \Illuminate\Http\Request  $request
 * @param Closure $next The next middleware.
 *
 * @return mixed Put ipAddress and userAgent to request.
 */

class DataParser
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        session()->put('ipAddress', $request->ip());
        session()->put('userAgent', $request->userAgent());

        $request->merge([
            'body' => $request->post(),
            'query' => $request->query(),
            'parameters' => $request->route()->parameters(),
            'token' => $token,
        ]);

        return $next($request);
    }
}
