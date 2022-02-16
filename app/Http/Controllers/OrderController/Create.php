<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;


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
