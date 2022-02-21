<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\User;

/**
 * Controller Get all User
 * @param \Illuminate\Http\Request  $request [query]{ page
*                                                       per_page
*                                                       sort_by
*                                                       sort_by
*                                                       sort }
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                       'users' => $users,
 */

class GetAll extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $page = $query['page'];
        $per_page = $query['per_page'];

        $users = new User();
        if(isset($query['sort_by'])){
            $users = $users->orderBy($query['sort_by'], $query['sort']);
        }

        $users = $users->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        );

        return Response::OK(
            data: [
                'users' => $users,
            ],
        );

    }
}
