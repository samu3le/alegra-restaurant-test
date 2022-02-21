<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\User;

/**
 * Controller Find User
 * @param \Illuminate\Http\Request  $request [query]{ id }
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                       'data' => $data,
 */

class Find extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $user = User::find($query['id']);

        $data = [
            'user' => [
                'nickname' => $user->nickname,
                'email' => $user->email
            ],
        ];

        return Response::OK(
            message: 'User found successfully.',
            data: $data,
        );

    }
}
