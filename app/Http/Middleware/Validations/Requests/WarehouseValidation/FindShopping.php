<?php

namespace App\Http\Middleware\Validations\Requests\WarehouseValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;

class FindShopping
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['query'], [
            'id' => [
                'required',
                'integer',
                'exists:ingredients,id',
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
            'query' => $query,
        ]);

        // $request->merge([
        //     'query' => $validator->validated(),
        // ]);

        return $next($request);
    }
}
