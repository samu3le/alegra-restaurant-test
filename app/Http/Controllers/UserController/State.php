<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\User;

class State extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $user = User::find($body['id']);

        $user->is_active = $body['is_active'];
        $user->save();

        $data = [
            'data' => [
                'user' => $user,
            ],
        ];

        return Response::CREATED(
            message: 'User updated successfully.',
            data: [
                $data,
            ]
        );
    }
}
