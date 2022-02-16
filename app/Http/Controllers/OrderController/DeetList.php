<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Services\Market;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Ingredient;

class DeetList extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $order = Order::find($body['id']);


        // $product_query = Product::where('is_active','true')
        // ->whereHas('recipe')
        // ->exists();

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

        // $market_quantity_sold = Market::Buy('potato');

        OrderDetails::insert($data_insert);

        $order->details->each(function($detail) {
            $detail->product->ingredients;
            foreach ($detail->product->ingredients as $ingredient) {
                if($ingredient->stock >= $detail->quantity){
                    $ingredient->stock -= $detail->quantity;
                    $ingredient->save();

                    $detail->state = 2;
                    $detail->save();
                }else{
                    $detail->state = 1;
                    $detail->save();
                }
            }
        });

        $ingredients = Ingredient::where('is_active', 'true')
            ->with(['products.orders_details' => function ($query) {
                $query->where('orders_details.state', 1);
            }])
            ->get()->toArray();

        foreach ($ingredients as $key => $ingredient) {
            $ingredients[$key]['requested_quantity'] = 0;
            foreach ($ingredient['products'] as $key_product => $product) {
                $quantity_recipe = $product['pivot']['quantity'];
                foreach ($product['orders_details'] as $key_order_details => $order_details) {
                    $ingredients[$key]['requested_quantity'] += $order_details['quantity'] * $quantity_recipe;
                }
            }
            $ingredients[$key]['requested_quantity'] = $ingredients[$key]['requested_quantity'] - $ingredient['stock'];
            unset($ingredients[$key]['products']);
        }

        return Response::CREATED(
            message: 'Order Details created successfully.',
            data: [
                'order' => $order,
            ]
        );
    }
}
