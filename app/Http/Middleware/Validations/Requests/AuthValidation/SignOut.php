<?php

namespace App\Http\Middleware\Validations\Requests\AuthValidation;

use Closure;
use Illuminate\Http\Request;

class SignOut
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
