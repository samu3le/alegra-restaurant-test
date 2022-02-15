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

        if(isset($body['state'])){
            $order->state = $body['state'];
        }

        $order->save();

        $random = Product::select('id')
                ->where('is_active','true')
                ->inRandomOrder()
                ->limit($order->quantity) // here is yours limit
                ->pluck('id')->toArray();

        $data_insert=[];
        for($i=0; $i < $order->quantity; $i++){
            for ($j=0; $j<sizeof($random); $j++) {
                if(sizeof($data_insert)==$order->quantity){
                    break;
                }
                $data_insert[] = [
                    'order_id' => $order->id,
                    'product_id' => $random[$j]
                ];
            }
        }

        OrderDetails::insert($data_insert);

        return Response::CREATED(
            message: 'Order created successfully.',
            data: [
                'order' => $order,
            ]
        );
    }
}
