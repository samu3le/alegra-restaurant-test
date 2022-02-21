<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Order;
use App\Models\OrderDetails;

class State extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $orderDetails = $body['orderDetails'];

        $state = $body['state'];
        if($state == 3){
            $stockValid = true;
            $detailQuantity = $orderDetails->quantity;

            $ingredients = $orderDetails->product->ingredients->toArray();
            foreach ($ingredients as $key => $ingredient) {
                $recipeQuantity = $ingredient['pivot']['quantity'];
                $stockQuantity = $ingredient['stock'];
                $stockValid = $stockQuantity >= ($detailQuantity * $recipeQuantity);
                if(!$stockValid){
                    break;
                }
            }

            if(!$stockValid){

                $orderDetails->state = 2;
                $orderDetails->save();

                return Response::UNPROCESSABLE_ENTITY(
                    message: 'Validation failed.',
                    errors: [
                        'id' => [
                            'The order detail has not enough stock.',
                            'The order detail was requested to warehouse.',
                        ],
                    ],
                );
            }

            $orderDetails->product->ingredients->each(function($ingredient) use ($detailQuantity) {
                $recipeQuantity = $ingredient->pivot->quantity;
                $ingredient->stock -= ($detailQuantity * $recipeQuantity);
                unset($ingredient->image);
                $ingredient->save();
            });

            $orderDetails->state = 3;
            $orderDetails->save();

        }elseif($state == 4){
            $orderDetails->state = 4;
            $orderDetails->save();

            $allOrderDetailsPrepared = true;
            $order = Order::where('id', $orderDetails->order_id)->with('details')->first();
            $order_details = $order['details'];
            foreach ($order_details as $key => $order_detail) {
                if($order_detail['state'] != 4){
                    $allOrderDetailsPrepared = false;
                    break;
                }
            }
            if($allOrderDetailsPrepared){
                Order::where('id', $orderDetails->order_id)->update(['state' => 3]);
            }
        }

        $orderDetails = $body['orderDetails'];
        $orderDetails = OrderDetails::find($orderDetails->id);
        $orderDetails->product->ingredients;
        
        return Response::OK(
            message: 'Order detail state updated.',
            data: [
                'order_details' => $orderDetails,
            ]
        );
    }
}
