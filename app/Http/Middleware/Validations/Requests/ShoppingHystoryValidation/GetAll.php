<?php

namespace App\Http\Middleware\Validations\Requests\ShoppingHystoryValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;



class GetAll
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
