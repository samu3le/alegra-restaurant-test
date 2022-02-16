<?php

namespace App\Http\Middleware\Validations\Requests\OrderValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;

use App\Models\Order;
use App\Models\Product;

class Create
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'quantity' => [
                'required',
                'integer',
                'min:1'
            ]
        ]);

        $product_query = Product::where('is_active','true')
        ->whereHas('recipe')
        ->exists();

        if(!$product_query){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: 'There are no registered products',
            );
        }
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
