<?php

namespace App\Http\Middleware\Validations\Requests\ProductValidation;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use App\Services\Validator;
use App\Services\Response;

/**
 * Validate data to Find Product
 * @param \Illuminate\Http\Request  $request { id }
 * @param Closure $next Controllers\ProductController\Find
 *
 * @return mixed Validate data , return array validated with error or next to cotroller.
 */

class Find
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['query'], [
            'id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
        ]);

        if($validator->fails()){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: $validator->errors(),
            );
        }

        $request->merge([
            'query' => $validator->validated(),
        ]);

        return $next($request);
    }
}
