<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;

/**
 * Controller Find Order
 * @param \Illuminate\Http\Request  $request [query]{ id }
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                       'order' => $order,
 */

class Find extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $order = Order::where('id',$query['id'])
        ->with('owner')
        ->with('details.product')
        ->first();

        return Response::OK(
            message: 'Order found successfully.',
            data: [
                'order' => $order,
            ]
        );
    }
}
