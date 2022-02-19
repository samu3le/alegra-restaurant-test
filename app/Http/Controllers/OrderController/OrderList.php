<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;

class OrderList extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];
        $orders = Order::with('details.product')->get();

        return Response::OK(
            data: [
                'orders' => $orders,
            ],
        );
    }
}
