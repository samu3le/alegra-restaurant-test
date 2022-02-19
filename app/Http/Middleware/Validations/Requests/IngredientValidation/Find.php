<?php

namespace App\Http\Middleware\Validations\Requests\IngredientValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\Response;

class Find
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['query'], [
            'id' => [
                'required',
                'integer',
                'exists:ingredients,id',
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
