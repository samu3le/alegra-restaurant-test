<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Product;

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
        $product->save();

        return Response::OK(
            message: 'Product updated successfully.',
            data: [
                'product' => $product,
            ],
        );
    }
}
