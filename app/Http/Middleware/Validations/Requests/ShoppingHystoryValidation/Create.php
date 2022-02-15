<?php

namespace App\Http\Middleware\Validations\Requests\ShoppingHystoryValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;

class Create
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'product_id' => [
                'ingredient_id',
                'integer',
                'exists:ingredients,id'
            ]
        ]);

        if($validator->fails()){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: $validator->errors(),
            );
        }
        $request->merge([
            'body' => $validator->validated(),
        ]);

        return $next($request);
    }
}
