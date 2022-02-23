<?php

namespace App\Http\Middleware\Validations\Requests\AuthValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Services\Validator;

/**
 * Validate data to sign in session
 * @param \Illuminate\Http\Request  $request {
 *                                              user,
                                                * password,
                                                * remember_me,
                                            *}
 * @param Closure $next Controllers\AuthController\SignIn.
 *
 * @return mixed Validate data return array validated with error or next to cotroller.
 */
class SignIn
{

    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'user' => ['required', 'string', 'min:3', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'max:50'],
            'remember_me'=>['boolean'],
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
