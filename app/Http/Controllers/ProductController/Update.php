<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Product;
use App\Models\Recipe;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $product = Product::find($body['id']);

        if(isset($body['name'])){
            $product->name = $body['name'];
        }
        if(isset($body['is_active'])){
            $product->is_active = $body['is_active'];
        }
        if(isset($body['image'])){
            $productModel = new Product();
            $productModel->deleteFile($product->image);
            $product->image = $body['image'];
        }
        $product->save();

        if(isset($body['ingredients'])){
            Recipe::where('product_id', $product->id)->delete();
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
        }

        $product->ingredients;
        $product->orders_details;

        return Response::OK(
            message: 'Product updated successfully.',
            data: [
                'product' => $product,
            ],
        );
    }
}
