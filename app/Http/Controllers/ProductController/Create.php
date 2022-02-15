<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Product;

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

        $product->save();

        return Response::CREATED(
            message: 'Product created successfully.',
            data: [
                'product' => $product,
            ]
        );
    }
}
