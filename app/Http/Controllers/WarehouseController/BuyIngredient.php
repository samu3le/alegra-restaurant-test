<?php

namespace App\Http\Controllers\WarehouseController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Market;
use App\Services\Response;

use App\Models\Order;
use App\Models\ShoppingHistory;
use App\Models\Ingredient;

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
