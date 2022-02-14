<?php

namespace App\Http\Middleware\Validations\Requests\RoleValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Response;

class Update
{
    public function handle(Request $request, Closure $next)
    {

        if(isset($request['name'])){
            $request['name'] = strtolower($request['name']);
        }
        $lowerCaseToArray = array(
            'name' => $request['name'],
        );
        $validate = array_merge($request['body'], $lowerCaseToArray);

        $validator = Validator::make($validate, [
            'id' => [
                'required',
                'integer',
                'exists:roles,id'
            ],
            'name' => [
                'string',
                'max:255',
                !empty($request->id) ? 'unique:roles,name,'.$request->id :null
            ],
            'is_active' => [
                'boolean',
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
