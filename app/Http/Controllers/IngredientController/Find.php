<?php

namespace App\Http\Controllers\IngredientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Ingredient;

class Find extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $ingredient = Ingredient::find($query['id']);

        return Response::OK(
            message: 'Ingredient found successfully.',
            data: [
                'ingredient' => $ingredient,
            ]
        );
    }
}
