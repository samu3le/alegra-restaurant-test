<?php

namespace App\Http\Controllers\IngredientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Ingredient;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $ingredient = Ingredient::find($body['id']);

        if(isset($body['name'])) {
            $ingredient->name = $body['name'];
        }
        if(isset($body['stock'])) {
            $ingredient->stock = $body['stock'];
        }
        if(isset($body['is_active'])) {
            $ingredient->is_active = $body['is_active'];
        }
        if(isset($body['image'])){
            $ingredientModel = new Ingredient();
            $ingredientModel->deleteFile($ingredient->image);
            $ingredient->image = $body['image'];
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
