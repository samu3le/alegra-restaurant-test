<?php

namespace App\Http\Middleware\Validations\Requests\UserValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class State
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'id' => ['required', 'integer', 'exists:users,id'],
            'is_active' => ['required','boolean']
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
