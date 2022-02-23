<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;

/**
 * Controller generate random detail of Order
 * @param \Illuminate\Http\Request  $request [body]{ id }
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::CREATED,
 *                       'order' => $order,
 */

class DeetList extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $order = Order::find($body['id']);

        if($order->state != 1){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: 'The order has already been processed.',
            );
        }

        $random = Product::select('id')
        ->where('is_active','true')
        ->whereHas('recipe')
        ->inRandomOrder()
        ->limit($order->quantity) // here is yours limit
        ->pluck('id')->toArray();

        OrderDetails::where('order_id', $order->id)->delete();

        $products = [];
        foreach (range(0, $order->quantity - 1) as $key => $number) {
            $id = $random[array_rand($random, 1)];
            if(isset($products[$id])) {
                $products[$id]++;
            }else {
                $products[$id] = 1;
            }
        }

        $data_insert = [];
        foreach ($products as $key => $value) {
            $data_insert[] = [
                'order_id' => $order->id,
                'product_id' => $key,
                'quantity' => $value,
            ];
        }

        OrderDetails::insert($data_insert);

        $order->state = 2;
        $order->save();

        $order->details->each( function($detail) {
            $detail->product->ingredients;
        });


        return Response::CREATED(
            message: 'Order Details created successfully.',
            data: [
                'order' => $order,
            ]
        );
    }
}
