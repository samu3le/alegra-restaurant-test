<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Product;

/**
 * Controller find Product
 * @param \Illuminate\Http\Request  $request [query]{ id }
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                       'product' => $product,,
 */

class Find extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $product = Product::find($query['id']);
        $product->ingredients;
        $product->orders_details;

        return Response::OK(
            message: 'Products found successfully.',
            data: [
                'product' => $product,
            ]
        );
    }
}
