<?php

namespace App\Http\Middleware\Validations\Requests\ProductValidation;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

use App\Models\Product;
use App\Services\Validator;
use App\Services\Response;

/**
 * Validate data to get all Product
 * @param \Illuminate\Http\Request  $request { sort_by,sort }
 * @param Closure $next Controllers\ProductController\GetAll
 *
 * @return mixed Validate data , return array validated with error or next to cotroller.
 */

class GetAll
{
    public function handle(Request $request, Closure $next)
    {
        $product = new Product();

        $validator = Validator::make($request['query'], [
            'sort_by' => ['nullable', 'string', 'in:' . implode(',', $product->getFillable())],
            'sort' => ['nullable', 'string', 'in:asc,desc'],
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
