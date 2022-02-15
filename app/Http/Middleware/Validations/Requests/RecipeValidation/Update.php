<?php

namespace App\Http\Middleware\Validations\Requests\RecipeValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;

use Illuminate\Validation\Rule;

use App\Http\Middleware\ListContent;
use App\Http\Middleware\ExistList;
use App\Http\Middleware\ListNotRepeat;

class Update
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'product_id' => ['required','integer','exists:products,id','exists:recipes,product_id'],
            'ingredients' => [
               'required',
               'array',
                Rule::when(is_array($request->ingredients), [
                    new ListContent('integer'),
                    new ExistList('ingredients', 'id'),
                    new ListNotRepeat()
                ])
            ],
            'quantities' => [
                'required',
                'array',
                'min:1',
                Rule::when(is_array($request->quantities), [
                    new ListContent('integer')
                ])
            ],
            'is_active' => ['boolean']
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
