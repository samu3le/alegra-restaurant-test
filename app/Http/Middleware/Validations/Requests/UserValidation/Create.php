<?php

namespace App\Http\Middleware\Validations\Requests\UserValidation;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use App\Services\Validator;
use App\Services\Response;

use App\Models\User;

class Create
{
    public function handle(Request $request, Closure $next)
    {

        $validator = Validator::make($request['body'], [
            'email' => ['required','email','min:6', 'max:50','iunique:users'],
            'nickname' => ['required', 'alpha_num','min:6', 'max:10','iunique:users'],
            'password' => ['required', Password::defaults()],
            'passwordConfirmation' => ['required','same:password'],
            'role' => ['integer', 'in:'.implode(",", array_keys(User::ROLES))],
            'is_active' => ['boolean'],
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
