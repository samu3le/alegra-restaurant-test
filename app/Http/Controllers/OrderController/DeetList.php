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

        $get_order_details = OrderDetails::where('order_id', $order->id)
        ->get();

        foreach($get_order_details as $order_details_dlt){
            $order_details_dlt->delete();
        }

        $data_insert=[];
        for($i=0; $i < $order->quantity; $i++){
            for ($j=0; $j<sizeof($random); $j++) {
                if(sizeof($data_insert) == $order->quantity){
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
            message: 'Order Details created successfully.',
            data: [
                'order_details' => $data_insert,
            ]
        );
    }
}
