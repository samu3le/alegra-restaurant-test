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

        $random = Product::select('id')
            ->where('is_active','true')
            ->pluck('id')->toArray();

        if(sizeof($random) != 0){
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
        }else{
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: 'Products is empty',
            );
        }

    }
}
