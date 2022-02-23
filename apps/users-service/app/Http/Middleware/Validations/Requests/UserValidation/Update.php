<?php

namespace App\Http\Middleware\Validations\Requests\UserValidation;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use App\Services\Validator;
use App\Services\Response;

use App\Models\User;

/**
 * Validate data to Update User
 * @param \Illuminate\Http\Request  $request {
                                                * id,
                                                * email,
                                                * nickname,
                                                * password,
                                                * passwordConfirm,
                                                *role,
                                                *is_active
                                            *}
 * @param Closure $next Controllers\UserController\Update.
 *
 * @return mixed Validate data , return array validated with error or next to cotroller.
 */

class Update
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'id' => [
                'required',
                'integer',
                'exists:users',
            ],
            'email' => [
                'email',
                'min:6',
                'max:50',
                isset($request->id) ? 'iunique:users,email,'.$request->id :null,
            ],
            'nickname' => [
                'alpha_num',
                'min:6',
                'max:50',
                isset($request->id) ? 'iunique:users,nickname,'.$request->id :null,
            ],
            'password' => [
                Password::defaults(),
            ],
            'passwordConfirmation' => [
                'same:password',
                'required_with:password',
            ],
            'role' => [
                'string',
                'in:'.implode(",", array_values(User::ROLES)),
            ],
            'is_active' => [
                'boolean',
            ]
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
