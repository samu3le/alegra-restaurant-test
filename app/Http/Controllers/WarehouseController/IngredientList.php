<?php

namespace App\Http\Controllers\WarehouseController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Ingredient;

/**
 * Controller get all data ingredients list on warehouse
 * @param \Illuminate\Http\Request  $request {
 *                                              query
*                                               page
*                                               per_page
                                            *}
 * @param Closure return App\Services\Response Response
 *
 * @return mixed return Response::OK, 'ingredients' => $ingredients,.
 */

class IngredientList extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $page = $query['page'];
        $per_page = $query['per_page'];


        $ingredients = Ingredient::where('is_active', 'true')
        ->with(['products.orders_details' => function ($query) {
            $query->whereIn('orders_details.state', [1,2]);
        }]);

        $ingredients = $ingredients->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        )
        ->toArray();

        foreach ($ingredients['data'] as $key => $ingredient) {
            $ingredients['data'][$key]['requested_quantity'] = 0;
            foreach ($ingredient['products'] as $key_product => $product) {
                $quantity_recipe = $product['pivot']['quantity'];
                foreach ($product['orders_details'] as $key_order_details => $order_details) {
                    $ingredients['data'][$key]['requested_quantity'] += $order_details['quantity'] * $quantity_recipe;
                }
            }
            $result = $ingredients['data'][$key]['requested_quantity'] - $ingredient['stock'];
            $no_stock = ($result < 0 ? false : true);
            unset($ingredients['data'][$key]['products']);
            $ingredients['data'][$key]['no_stock'] = $no_stock;
        }

        return Response::OK(
            data: [
                'ingredients' => $ingredients,
            ],
        );
    }
}
