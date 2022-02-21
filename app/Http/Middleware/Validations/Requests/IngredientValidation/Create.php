<?php

namespace App\Http\Middleware\Validations\Requests\IngredientValidation;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

use App\Http\Middleware\IsBase64;
use App\Services\Validator;
use App\Services\Response;

/**
 * Validate data to Create Ingredient
 * @param \Illuminate\Http\Request  $request {
 *                                              name,
                                                * key,
                                                * stock,
                                                * is_active,
                                                * image
                                            *}
 * @param Closure $next Controllers\IngredientController\Create.
 *
 * @return mixed Validate data return array validated with error or next to cotroller.
 */

class Create
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'name' => ['required','string','min:2', 'max:25','iunique:ingredients'],
            'key' => ['required', 'string','min:2', 'max:25','iunique:ingredients'],
            'stock' => ['integer', 'min:5'],
            'is_active' => ['boolean'],
            'image' => [
                'string',
                new IsBase64(
                    types: ['png','jpg', 'jpeg', 'gif'],
                    size: 2048,
                ),
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
