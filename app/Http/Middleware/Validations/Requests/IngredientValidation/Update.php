<?php

namespace App\Http\Middleware\Validations\Requests\IngredientValidation;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

use App\Http\Middleware\IsBase64;
use App\Services\Validator;
use App\Services\Response;

class Update
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'id' => ['required','integer','exists:ingredients,id'],
            'name' => [
                'string','min:2','max:25',
                !empty($request->id) ? 'iunique:ingredients,name,'.$request->id :null
            ],
            'key' => [
                'string','min:2', 'max:25',
                !empty($request->id) ? 'iunique:ingredients,key,'.$request->id :null
            ],
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
