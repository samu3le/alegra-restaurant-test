<?php

namespace App\Http\Controllers\IngredientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Ingredient;

class Create extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $ingredient = new Ingredient;

        $ingredient->name = $body['name'];
        $ingredient->key = $body['key'];

        if(isset($body['is_active'])){
            $ingredient->is_active = $body['is_active'];
        }
        if(isset($body['stock'])) {
            $ingredient->stock = $body['stock'];
        }
        if(isset($body['image'])){
            $ingredient->image = $body['image'];
        }

        $ingredient->save();

        return Response::CREATED(
            message: 'Ingredient created successfully.',
            data: [
                'ingredient' => $ingredient,
            ]
        );
    }
}
