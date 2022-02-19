<?php

namespace App\Http\Controllers\WarehouseController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\ShoppingHistory;
use App\Models\Ingredient;

class FindShopping extends Controller
{
    public function __invoke(Request $request)
    {

        $query = $request['query'];
        $page = $query['page'];
        $per_page = $query['per_page'];


        $shoppingHistory = ShoppingHistory::where('ingredient_id',$query['id']);
        $shoppingHistory = $shoppingHistory->with('owner');

        $ingredient =  Ingredient::find($query['id']);

        if(isset($query['sort_by'])){
            $shoppingHistory = $shoppingHistory->orderBy($query['sort_by'], $shoppingHistory['sort']);
        }

        $shoppingHistory = $shoppingHistory->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        );

        // $shoppingHistory = $shoppingHistory->shoppings->each(function($shopping){
        //     $shopping->owner;
        // });

        return Response::OK(
            message: 'Shopping history found successfully.',
            data: [
                'ingredient' => $ingredient,
                'shoppings' => $shoppingHistory,
            ]
        );
    }
}
