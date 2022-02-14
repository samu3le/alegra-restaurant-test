<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\User;

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
            data: [
                $data,
            ]
        );

    }
}
