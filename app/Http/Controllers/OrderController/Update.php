<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;

class Update extends Controller
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

        if(sizeof($random) != 0){

            if((isset($body['state'])) && ($body['state'] != $order->state)){
                $order->state = $body['state'];
            }

            if(isset($body['quantity']) && ($order->state ==1)){
                $order->quantity = $body['quantity'];

                $get_order_details = OrderDetails::where('order_id', $order->id)
                ->get();

                foreach($get_order_details as $order_details_dlt){
                    $order_details_dlt->delete();
                }

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
