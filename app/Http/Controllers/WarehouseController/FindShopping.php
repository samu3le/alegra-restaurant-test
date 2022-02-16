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
        $page = $query['page'];
        $per_page = $query['per_page'];

        $ingredient = Ingredient::where('id', $query['id']);
        $ingredient = $ingredient->with('shopping')->get();

        return Response::OK(
            message: 'Ingredient found successfully.',
            data: [
                'ingredient' => $ingredient,
            ]
        );
    }
}
