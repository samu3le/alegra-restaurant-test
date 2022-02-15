<?php

namespace App\Http\Controllers\RecipeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;
use App\Models\Recipe;

class GetAll extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $page = $query['page'];
        $per_page = $query['per_page'];

        $recipes = Recipe::join('ingredients','ingredients.id','=','recipes.ingredient_id');

        // $users = User::join('posts', 'posts.user_id', '=', 'users.id')
        // ->join('comments', 'comments.post_id', '=', 'posts.id')
        // ->get(['users.*', 'posts.descrption']);

        if(isset($query['sort_by'])){
            $recipes = $recipes->orderBy($query['sort_by'], $query['sort_direction']);
        }

        $recipes = $recipes->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        );

        return Response::OK(
            data: [
                'recipes' => $recipes,
            ],
        );
    }
}
