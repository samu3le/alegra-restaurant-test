<?php

namespace App\Http\Middleware\Validations\Requests\ProductValidation;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

use App\Http\Middleware\IsBase64;
use App\Services\Validator;
use App\Services\Response;

use Illuminate\Validation\Rule;

use App\Http\Middleware\ListContent;
use App\Http\Middleware\ExistList;
use App\Http\Middleware\ListNotRepeat;

/**
 * Validate data to Create Product
 * @param \Illuminate\Http\Request  $request {
                                                * name,
                                                * is_active,
                                                * image,
                                                * ingredients { id_ingredient , quantity }
                                            *}
 * @param Closure $next Controllers\ProductController\Create.
 *
 * @return mixed Validate data , return array validated with error or next to cotroller.
 */

class Create
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'name' => ['required','string','min:5', 'max:50','iunique:products'],
            'is_active' => ['boolean'],
            'image' => [new IsBase64(
                types: ['png','jpg', 'jpeg', 'gif'],
                size: 2048,
            )],
            'ingredients' => [
                'required',
                'array',
            ],
        ]);

        if($validator->fails()){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: $validator->errors(),
            );
        }

        $body = $validator->validated();

        $ingredients = $validator->validated()['ingredients'] ?? [];
        if(!empty($ingredients)){

            $ingredients_ids = array_keys($ingredients);
            $ingredients_quantities = array_values($ingredients);

            $ingredientes_refactored = [];
            foreach($ingredients_ids as $ingredient_id){
                $ingredientes_refactored[] = [
                    'id' => $ingredient_id,
                    'quantity' => $ingredients[$ingredient_id],
                ];
            }

            $to_validated = [
                'ingredients' => $ingredients_ids,
                'quantities' => $ingredients_quantities,
            ];

            $validator_ingredients = Validator::make($to_validated, [
                'ingredients' => [
                    'required',
                    'array',
                    new ListContent('integer'),
                ],
                'quantities' => [
                    'required',
                    'array',
                    'min:' . count($ingredients_ids),
                    new ListContent('integer'),
                ],
            ]);
            if($validator_ingredients->fails()){
                return Response::UNPROCESSABLE_ENTITY(
                    message: 'Validation failed.',
                    errors: $validator_ingredients->errors(),
                );
            }

            $validator_ingredients = Validator::make($to_validated, [
                'ingredients' => [
                    new ExistList('ingredients', 'id'),
                    new ListNotRepeat(),
                ],
                'quantities' => [
                    'required',
                ],
            ]);
            if($validator_ingredients->fails()){
                return Response::UNPROCESSABLE_ENTITY(
                    message: 'Validation failed.',
                    errors: $validator_ingredients->errors(),
                );
            }

            $body['ingredients'] = $ingredientes_refactored;
        }

        $request->merge([
            'body' => $body,
        ]);

        return $next($request);
    }
}
