<?php

namespace App\Http\Middleware\Validations\Requests\OrderValidation;

use Closure;
use Illuminate\Http\Request;

use App\Services\Validator;
use App\Services\Response;

use App\Models\Order;
use App\Models\OrderDetails;

class State
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'id' => [
                'required',
                'integer',
                'exists:orders_details',
            ],
            'state' => [
                'required',
                'integer', 
                'in:'.implode(",", array_keys(OrderDetails::STATE)),
            ],
        ]);

        if($validator->fails()){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: $validator->errors(),
            );
        }

        $body = $validator->validated();

        $orderDetails = OrderDetails::find($body['id']);

        if($orderDetails->state >= $body['state']){
            if($orderDetails->state == $body['state']){
                return Response::UNPROCESSABLE_ENTITY(
                    message: 'Validation failed.',
                    errors: [
                        'state' => ['The order has already been ' . OrderDetails::STATE[$body['state']] . '.'],
                    ],
                );
            }else{
                $stateMustBeGreater = isset(OrderDetails::STATE[$body['state'] - 1]) ? OrderDetails::STATE[$body['state'] - 1] : OrderDetails::STATE[$body['state']];
                return Response::UNPROCESSABLE_ENTITY(
                    message: 'Validation failed.',
                    errors: [
                        'state' => ['The order detail must be state "'.$stateMustBeGreater.'" to change the state.'],
                    ],
                );
            }
        }

        $body['orderDetails'] = $orderDetails;

        $request->merge([
            'body' => $body,
        ]);

        return $next($request);
    }
}
