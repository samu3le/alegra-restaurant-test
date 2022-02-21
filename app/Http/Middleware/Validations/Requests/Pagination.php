<?php

namespace App\Http\Middleware\Validations\Requests;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Services\Response;

/**
 * validate data pagination
 * @param \Illuminate\Http\Request  $request {page, per_page, search}
 * @param Closure $next The next middleware.
 *
 * @return mixed Validate data , return data validated.
 */

class Pagination
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['query'], [
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string'],
        ]);

        if($validator->fails()){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: $validator->errors(),
            );
        }

        $query = $request['query'];

        $query['page'] = $query['page'] ?? 1;
        $query['per_page'] = $query['per_page'] ?? 10;

        $request->merge([
            'query' => $query,
        ]);

        return $next($request);
    }
}
