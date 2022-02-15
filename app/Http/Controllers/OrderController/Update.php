<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;

use App\Models\Product;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $order = Order::find($body['id']);

        $random = Product::select('id')
            ->where('is_active','true')
            ->pluck('id')->toArray();

        if(sizeof($random) != 0){

            if((isset($body['state'])) && ($body['state'] != $order->state)){
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

        }else{
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: 'Products is empty',
            );
        }
    }
}
