<?php

namespace App\Http\Controllers\RecipeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Recipe;

class Find extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $recipe = Recipe::where('product_id', $query['product_id'])->get();

        return Response::OK(
            message: 'Recipe found successfully.',
            data: [
                'recipe' => $recipe,
            ]
        );
    }
}
