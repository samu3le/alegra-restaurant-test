<?php

namespace App\Http\Middleware\Validations\Requests\ProductValidation;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

use App\Http\Middleware\IsBase64;
use App\Services\Validator;
use App\Services\Response;

class Update
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'id' => ['required','integer','exists:products,id'],
            'name' => [
                'string','min:5','max:50',
                !empty($request->id) ? 'iunique:products,name,'.$request->id :null
            ],
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
