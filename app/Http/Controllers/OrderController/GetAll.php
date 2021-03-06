<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;
use App\Models\User;


/**
 * Controller get all Order
 * @param \Illuminate\Http\Request  $request [query]{ page
*                                                       per_page
*                                                       sort_by
*                                                       sort }
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                       'orders' => $orders,
 */

class GetAll extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $page = $query['page'];
        $per_page = $query['per_page'];

        $orders = Order::with('owner');

        if(isset($query['sort_by'])){
            $orders = $orders->orderBy($query['sort_by'], $query['sort']);
        }

        $orders = $orders->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        );

        return Response::OK(
            data: [
                'orders' => $orders,
            ],
        );
    }
}
