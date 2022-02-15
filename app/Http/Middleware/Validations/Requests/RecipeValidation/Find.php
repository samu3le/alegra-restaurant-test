<?php

namespace App\Http\Middleware\Validations\Requests\RecipeValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;

class Find
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['query'], [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
                'exists:recipes,product_id'
            ],
        ]);

        if($validator->fails()){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: $validator->errors(),
            );
        }

        $request->merge([
            'query' => $validator->validated(),
        ]);

        return $next($request);
    }
}
