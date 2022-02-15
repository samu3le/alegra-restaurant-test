<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\User;

class Create extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $user = new User;

        $user->nickname = $body['nickname'];
        $user->email = $body['email'];
        $user->password = $body['password'];
        if(isset($body['role'])) {
            $user->role = $body['role'];
        }
        if(isset($body['is_active'])){
            $user->is_active = $body['is_active'];
        }
        $user->save();

        $data = [
            'user' => [
                'nickname' => $user->nickname,
                'email' => $user->email,
                'token' => $user->token,
            ],
        ];

        return Response::CREATED(
            message: 'User created successfully.',
            data: $data,
        );

    }
}
