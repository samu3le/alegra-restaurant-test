<?php

namespace App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Order;

class OrderList extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];
        $page = $query['page'];
        $per_page = $query['per_page'];

        $orders_requested = Order::where('state','1')
        ->with('details.product');
        $arr_requested = $orders_requested->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        )->toArray();
        $arr_requested['quantity'] = $orders_requested->count();

        $orders_pending = Order::where('state','2')
        ->with('details.product');
        $arr_pending = $orders_pending->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        )->toArray();
        $arr_pending['quantity'] = $orders_pending->count();

        $orders_dispatched = Order::where('state','3')
        ->with('details.product');
        $arr_dispatched = $orders_dispatched->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        )->toArray();
        $arr_dispatched['quantity'] = $orders_dispatched->count();

        if(isset($query['sort_by'])){

            $orders_requested = $orders_requested->orderBy($query['sort_by'], $query['sort']);
            $orders_pending = $orders_pending->orderBy($query['sort_by'], $query['sort']);
            $orders_dispatched = $orders_dispatched->orderBy($query['sort_by'], $query['sort']);

        }

        $arraySearch = [
           'orders_requested' => $arr_requested['quantity'],
           'orders_pending' => $arr_pending['quantity'],
           'orders_dispatched' => $arr_dispatched['quantity']
        ];

        $search = array_search(max($arraySearch),$arraySearch);

        $order = $orders_requested;

        if($search == 'orders_pending'){
            $order = $orders_pending;
        }
        if($search == 'orders_dispatched'){
            $order = $orders_dispatched;
        }

        $paginate_order = $order->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        )->toArray();

        $arr_requested = [
            'list' => $arr_requested['data'],
            'quantity' => $arr_requested['quantity'],
        ];
        $arr_pending = [
            'list' => $arr_pending['data'],
            'quantity' => $arr_pending['quantity'],
        ];
        $arr_dispatched = [
            'list' => $arr_dispatched['data'],
            'quantity' => $arr_dispatched['quantity'],
        ];

        $paginate_order['data'] = [
            'requested' => $arr_requested,
            'pending' => $arr_pending,
            'dispatched' => $arr_dispatched,
        ];

        return Response::OK(
            data: [
                'orders' => $paginate_order,
            ],
        );
    }
}
