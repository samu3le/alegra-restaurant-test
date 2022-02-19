<?php

namespace App\Http\Middleware\Validations\Requests\OrderValidation;

use Closure;
use Illuminate\Http\Request;

class OrderList
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
