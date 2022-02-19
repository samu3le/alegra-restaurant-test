<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;

class Find extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $order = Order::find($query['id']);

        return Response::OK(
            message: 'Order found successfully.',
            data: [
                'order' => $order,
            ]
        );
    }
}
