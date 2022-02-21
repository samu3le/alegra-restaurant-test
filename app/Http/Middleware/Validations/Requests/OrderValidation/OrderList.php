<?php

namespace App\Http\Middleware\Validations\Requests\OrderValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;

use App\Models\Order;

/**
 * Validate data to list Order to kitchen
 * @param \Illuminate\Http\Request  $request { sort_by,sort }
 * @param Closure $next Controllers\OrderController\Update.
 *
 * @return mixed Validate data, return array validated with error or next to cotroller.
 */

class OrderList
{
    public function handle(Request $request, Closure $next)
    {
        $order = new Order();

        $validator = Validator::make($request['query'], [
            'sort_by' => ['nullable', 'string', 'in:' . implode(',', $order->getFillable())],
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
