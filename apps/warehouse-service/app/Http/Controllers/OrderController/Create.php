<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;

/**
 * Controller Create Order
 * @param \Illuminate\Http\Request  $request [body]{ quantity }
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::CREATED,
 *                       'order' => $order,
 */

class Create extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $order = new Order;

        $order->quantity = $body['quantity'];
        $order->save();
        return Response::CREATED(
            message: 'Order created successfully.',
            data: [
                'order' => $order,
            ]
        );
    }
}
