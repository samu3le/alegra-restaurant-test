<?php

namespace App\Http\Controllers\WarehouseController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Ingredient;

class FindShopping extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $ingredient = Ingredient::find($query['id']);
        $ingredient = $ingredient->shoppings->each(function($shopping){
            $shopping->owner;
        });
        return Response::OK(
            message: 'Ingredient found successfully.',
            data: [
                'ingredient' => $ingredient,
            ]
        );
    }
}
