<?php

namespace App\Http\Controllers\IngredientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Ingredient;

class GetAll extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $page = $query['page'];
        $per_page = $query['per_page'];

        $ingredients = new Ingredient();
        if(isset($query['sort_by'])){
            $ingredients = $ingredients->orderBy($query['sort_by'], $query['sort']);
        }

        $ingredients = $ingredients->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        );

        return Response::OK(
            data: [
                'ingredients' => $ingredients,
            ],
        );
    }
}
