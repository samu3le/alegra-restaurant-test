<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $order = Order::find($body['id']);

        if(isset($body['quantity'])){
            $order->quantity = $body['quantity'];
        }
        if(isset($body['state'])){
            $order->state = $body['state'];
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
