<?php

namespace App\Http\Middleware\Validations\Requests\WarehouseValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;

use App\Models\ShoppingHistory;

class FindShopping
{
    public function handle(Request $request, Closure $next)
    {
        $shopping_model = new ShoppingHistory();

        $validator = Validator::make($request['query'], [
            'id' => [
                'required',
                'integer',
                'exists:ingredients,id',
            ],
            'sort_by' => [
                'nullable',
                'string',
                 'in:' . implode(',', $shopping_model->getFillable())
            ],
            'sort' => [
                'nullable',
                'string',
                 'in:asc,desc'
            ],
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
            'query' => $validator->validated(),
        ]);
        $request->merge([
            'query' => $query,
        ]);

        return $next($request);
    }
}
