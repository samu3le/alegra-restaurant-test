<?php

namespace App\Http\Controllers\WarehouseController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Market;
use App\Services\Response;

use App\Models\Order;
use App\Models\ShoppingHistory;
use App\Models\Ingredient;

/**
 * Controller buy a ingredient on warehouse, add stock
 * @param \Illuminate\Http\Request  $request { id }
 * @param Closure   use App\Services\Market request data  on https://recruitment.alegra.com/api/farmers-market/buy;
 *                  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                       'ingredient' => $market_quantity_sold,
 */

class BuyIngredient extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $ingredient = Ingredient::find($body['id']);

        $market_quantity_sold = Market::Buy($ingredient->key);

        // $market_quantity_sold['quantitySold'] Add errror


        $shopping_history = new ShoppingHistory;

        $shopping_history->ingredient_id = $ingredient->id;
        $shopping_history->quantity = $market_quantity_sold['quantitySold'];
        $shopping_history->save();

        unset($ingredient->image);
        $ingredient->stock = $ingredient->stock + $shopping_history->quantity;
        $ingredient->save();

        return Response::OK(
            message: 'Ingredient Buy successfully.',
            data: [
                'ingredient' => $market_quantity_sold,
            ],
        );
    }
}
