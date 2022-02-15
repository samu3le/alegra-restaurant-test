<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\User;

class SignUp extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        
        $user = new User;

        $user->nickname = $body['nickname'];
        $user->email = $body['email'];
        $user->password = $body['password'];

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
