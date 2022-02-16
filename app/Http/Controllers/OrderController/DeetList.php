<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;

class DeetList extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $order = Order::find($body['id']);

        $random = Product::select('id')
        ->where('is_active','true')
        ->inRandomOrder()
        ->limit($order->quantity) // here is yours limit
        ->pluck('id')->toArray();

        OrderDetails::where('order_id', $order->id)->delete();

        $data_insert = [];
        $count = 12;
        foreach (range(0, $count - 1) as $key => $number) {
            $data_insert[] = [
                'order_id' => $order->id,
                'product_id' => $random[array_rand($random, 1)],
            ];
        }

        OrderDetails::insert($data_insert);

        $order->details->each(function($item) {
            $item->product->ingredients;
        });

        return Response::CREATED(
            message: 'Order Details created successfully.',
            data: [
                'order' => $order,
            ]
        );
    }
}
