<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\User;


/**
 * Controller Sign up
 * @param \Illuminate\Http\Request  $request [body]{ nickname,
*                                                   email,
 *                                                  password}
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::CREATED,
 *                      'data' => $data,
 */


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
                'role' => $user->role,
            ],
        ];

        return Response::CREATED(
            message: 'User created successfully.',
            data: $data,
        );
    }
}
