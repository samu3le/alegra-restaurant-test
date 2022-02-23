<?php

namespace App\Http\Middleware\Validations\Requests\OrderValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;

/**
 * Validate data to generate random details to orders on kitchen
 * @param \Illuminate\Http\Request  $request { id }
 * @param Closure $next Controllers\OrderController\DeetList.
 *
 * @return mixed Validate data, return array validated with error or next to cotroller.
 */

class DeetList
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'id' => ['required','integer','exists:orders,id'],
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
