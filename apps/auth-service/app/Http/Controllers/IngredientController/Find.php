<?php

namespace App\Http\Controllers\IngredientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Ingredient;

/**
 * Controller Find Ingredient
 * @param \Illuminate\Http\Request  $request [query]{ id }
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                      'ingredient' => $ingredient,
 */

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
