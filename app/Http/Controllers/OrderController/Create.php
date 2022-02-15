<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;

class Create extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $order = new Order;

        $order->quantity = $body['quantity'];

        if(isset($body['state'])){
            $order->state = $body['state'];
        }

        $order->save();

        return Response::CREATED(
            message: 'Order created successfully.',
            data: [
                'order' => $order,
            ]
        );
    }
}
