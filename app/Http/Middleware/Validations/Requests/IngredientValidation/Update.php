<?php

namespace App\Http\Middleware\Validations\Requests\IngredientValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Middleware\IsBase64;
use App\Services\Response;

class Update
{
    public function handle(Request $request, Closure $next)
    {
        if(isset($request['name'])){
            $request['name'] = strtolower($request['name']);
        }
        if(isset($request['key'])){
            $request['key'] = strtolower($request['key']);
        }
        $lowerCaseToArray = array(
            'name' => $request['name'],
            'key' => $request['nickname']
        );
        $validate = array_merge($request['body'], $lowerCaseToArray);

        $validator = Validator::make($validate, [
            'id' => ['required','integer','exists:ingredients,id'],
            'name' => [
                'string','min:2','max:25',
                !empty($request->id) ? 'unique:ingredients,name,'.$request->id :null
            ],
            'key' => [
                'string','min:2', 'max:25',
                !empty($request->id) ? 'unique:ingredients,key,'.$request->id :null
            ],
            'stock' => ['integer', 'min:5'],
            'is_active' => ['boolean'],
            'image' => [new IsBase64(
                types: ['png','jpg', 'jpeg', 'gif'],
                size: 2048,
            )],
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
