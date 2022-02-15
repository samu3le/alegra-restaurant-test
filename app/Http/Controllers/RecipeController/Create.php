<?php

namespace App\Http\Controllers\RecipeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\Response;
use App\Models\Recipe;

class Create extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $product_id = $body['product_id'];
        // $user_auth = Auth::user()->id;

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

        return Response::CREATED(
            message: 'Recipe created successfully.',
            data: [
                'recipe' => $recipe_items,
            ]
        );
    }
}
