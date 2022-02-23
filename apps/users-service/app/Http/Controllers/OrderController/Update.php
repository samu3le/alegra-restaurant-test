<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;

use App\Models\Product;

/**
 * Controller Update Order
 * @param \Illuminate\Http\Request  $request [body]{ id }
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                       'order' => $order,
 */

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $order = Order::find($body['id']);

        if($order->state == 3){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: 'Order can`t be updated, State is: dispatched',
            );
        }
        if(isset($body['state'])){
            $order->state = $body['state'];
        }
        if(isset($body['quantity']) && ($order->state ==1)){
            $order->quantity = $body['quantity'];
        }

        $order->save();

        return Response::OK(
            message: 'Order updated successfully.',
            data: [
                'order' => $order,
            ]
        );
    }
}
