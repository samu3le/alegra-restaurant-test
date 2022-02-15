<?php

namespace App\Http\Middleware\Validations\Requests\ProductValidation;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

use App\Http\Middleware\IsBase64;
use App\Services\Validator;
use App\Services\Response;

class Create
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'name' => ['required','string','min:5', 'max:50','iunique:products'],
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
