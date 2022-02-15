<?php

namespace App\Http\Controllers\IngredientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Ingredient;


class State extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $ingredient = Ingredient::find($body['id']);

        if(isset($body['is_active'])) {
            $ingredient->is_active = $body['is_active'];
        }

        $ingredient->save();

        return Response::OK(
            message: 'Ingredient updated successfully.',
            data: [
                'ingredient' => $ingredient,
            ],
        );
    }
}
