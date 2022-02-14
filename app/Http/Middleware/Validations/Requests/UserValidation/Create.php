<?php

namespace App\Http\Middleware\Validations\Requests\UserValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use App\Services\Response;

class Create
{
    public function handle(Request $request, Closure $next)
    {
        if(isset($request['email'])){
            $request['email'] = strtolower($request['email']);
        }
        if(isset($request['nickname'])){
            $request['nickname'] = strtolower($request['nickname']);
        }
        $lowerCaseToArray = array(
            'name' => $request['name'],
            'nickname' => $request['nickname']
        );
        $validate = array_merge($request['body'], $lowerCaseToArray);

        $validator = Validator::make($validate, [
            'email' => ['required','email','min:6', 'max:50','unique:users'],
            'nickname' => ['required', 'alpha_num','min:6', 'max:10','unique:users'],
            'password' => ['required', Password::defaults()],
            'passwordConfirmation' => ['required','same:password'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
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
