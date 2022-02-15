<?php

namespace App\Http\Controllers\RecipeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Recipe;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $recipe_model = new Recipe();
        $product_id = $body['product_id'];
        // $user_auth = Auth::user()->id;

        $get_ingredients = Recipe::where('product_id', $product_id)
        ->get();

        foreach($get_ingredients as $ingredient_dlt){
            $ingredient_dlt->delete();
        }

        $recipe_items = [];
        foreach ($body['ingredients'] as $key => $ingredient) {

            $recipe_items[] = [
                'product_id' => $product_id,
                'ingredient_id' => $ingredient,
                'quantity' => $body['quantities'][$key],
                'created_by' => 1//$user_auth
            ];
        }
        Recipe::insert($recipe_items);

        return Response::OK(
            message: 'Recipe updated successfully.',
            data: [
                'recipe' => $recipe_items,
            ]
        );
    }
}
