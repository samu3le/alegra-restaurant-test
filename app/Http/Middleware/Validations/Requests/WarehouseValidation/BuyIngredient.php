<?php

namespace App\Http\Middleware\Validations\Requests\WarehouseValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;

class BuyIngredient
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'id' => [
                'required',
                'integer',
                'exists:ingredients,id'
            ],
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
