<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Services\Response;

use App\Models\User;

/**
 * Controller Sign in
 * @param \Illuminate\Http\Request  $request [body]{ user,
*                                                   nickname,
 *                                                  password}
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                      'data' => $data,
 */

class SignIn extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $column = '';
        $value = '';
        if (filter_var($body['user'], FILTER_VALIDATE_EMAIL)) {
            $column = 'email';
            $value = $body['user'];
        }else{
            $column = 'nickname';
            $value = $body['user'];
        }

        $user = User::where($column, $value)->get()->first();
        if(!$user){
            return Response::UNAUTHORIZED(
                errors: [
                    'user' => ['Credentials are incorrect.'],
                ],
            );
        }
        if(!Hash::check($body['password'], $user->password)){
            return Response::UNAUTHORIZED(
                errors: [
                    'user' => ['Credentials are incorrect.'],
                ],
            );
        }
        if(!$user->is_active){
            return Response::UNAUTHORIZED(
                errors: [
                    'user' => ['User is not active.'],
                ],
            );
        }

        $remember_me = isset($body['remember_me']) ? $body['remember_me'] : false;

        try {
            $user->generateToken($remember_me);
        } catch (\Throwable $th) {
            \Log::error([
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);
        }

        $data = [
            'user' => [
                'nickname' => $user->nickname,
                'email' => $user->email,
                'token' => $user->token,
                'role' => $user->role,
            ],
        ];

        return Response::OK(
            data: $data,
            message: 'User signed in successfully.',
        );
    }
}
