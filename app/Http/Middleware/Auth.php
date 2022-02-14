<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth as AuthLaravel;

use App\Services\JWT;
use App\Services\Response;

class Auth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request['token'];

        $validator = Validator::make([
            'token' => $token,
        ], [
            'token' => ['required', 'string'],
        ]);

        if($validator->fails()){
            return Response::UNAUTHORIZED();
        }

        $token = $validator->validated()['token'];

        $jwt = new JWT();
        $jwt->VerifyToken($token);
        if(!$jwt->isValid()){
            return Response::UNAUTHORIZED();
        }

        AuthLaravel::loginUsingId($jwt::session()->user_id);

        return $next($request);
    }
}
