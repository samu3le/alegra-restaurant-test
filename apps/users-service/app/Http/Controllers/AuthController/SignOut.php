<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Services\JWT;
use App\Services\Response;

use App\Model\User;


/**
 * Controller Sign Out
 * @param Illuminate\Support\Facades\Auth;  Auth::user()
 * @param Closure  return App\Services\Response Response
 *
 * @return mixed return Response::OK,
 *                      message: 'Logout success.',
 */


class SignOut extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $request['session']->close();

        return Response::OK(
            message: 'Logout success.',
        );
    }
}
