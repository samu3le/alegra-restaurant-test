<?php

namespace App\Http\Middleware\Validations\Requests\AuthValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Services\Validator;

class SignUp
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'email' => ['required', 'string', 'email', 'max:50', 'iunique:users'],
            'nickname' => ['required', 'string', 'max:50', 'iunique:users'],
            'password' => ['required', 'string', 'min:6', 'max:50'],
            'passwordConfirmation' => ['required', 'string', 'min:6', 'max:50', 'same:password'],
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
