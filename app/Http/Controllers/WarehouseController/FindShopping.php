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

        $shoppings = Ingredient::find($query['id']);
        $shoppings = $ingredient->shoppings->each(function($shopping){
            $shopping->owner;
        });
        return Response::OK(
            message: 'Shopping history found successfully.',
            data: [
                'shoppings' => $shoppings,
            ]
        );
    }
}
