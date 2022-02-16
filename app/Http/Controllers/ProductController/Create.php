<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Product;
use App\Models\Recipe;

class Create extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $product = new Product();

        $product->name = $body['name'];
        if(isset($body['is_active'])){
            $product->is_active = $body['is_active'];
        }
        if(isset($body['image'])){
            $product->image = $body['image'];
        }

        $product->save();

        $recipe = [];
        foreach ($body['ingredients'] as $key => $ingredient) {
            $recipe[] = [
                'product_id' => $product->id,
                'ingredient_id' => $ingredient['id'],
                'quantity' => $ingredient['quantity'],
                'created_by' => \Auth::user()->id,
            ];
        }
        Recipe::insert($recipe);

        $product->ingredients;
        $product->orders_details;

        return Response::CREATED(
            message: 'Product created successfully.',
            data: [
                'product' => $product,
            ]
        );
    }
}
