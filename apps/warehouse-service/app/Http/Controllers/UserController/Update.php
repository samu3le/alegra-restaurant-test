<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\User;

/**
 * Controller Update User
 * @param \Illuminate\Http\Request  $request [body]{
 *                                              id,
 *                                              nickname
*                                               email
*                                               password
*                                               role
*                                               is_active }
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                       'data' => $data,
 */

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $user = User::find($body['id']);

        if(!empty($body['nickname'])){
            $user->nickname = $body['nickname'];
        }
        if(!empty($body['email'])){
            $user->email = $body['email'];
        }
        if(!empty($body['password'])){
            $user->password = $body['password'];
        }
        if(isset($body['role'])) {
            $user->role = $body['role'];
        }
        if(isset($body['is_active'])){
            $user->is_active = $body['is_active'];
        }
        $user->save();

        return Response::OK(
            message: 'User updated successfully.',
            data: [
                'user' => $user,
            ],
        );
    }
}
