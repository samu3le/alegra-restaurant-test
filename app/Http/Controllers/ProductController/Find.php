<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Product;

class Find extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $product = Product::find($query['id']);

        return Response::OK(
            message: 'Products found successfully.',
            data: [
                'product' => $product,
            ]
        );
    }
}
