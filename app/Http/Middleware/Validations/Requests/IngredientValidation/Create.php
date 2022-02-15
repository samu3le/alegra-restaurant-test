<?php

namespace App\Http\Middleware\Validations\Requests\IngredientValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\Response;

class Create
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
            'key' => $request['key']
        );
        $validate = array_merge($request['body'], $lowerCaseToArray);

        $validator = Validator::make($validate, [
            'name' => ['required','string','min:2', 'max:25','unique:ingredients'],
            'key' => ['required', 'string','min:2', 'max:25','unique:ingredients'],
            'stock' => ['integer', 'min:5'],
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
