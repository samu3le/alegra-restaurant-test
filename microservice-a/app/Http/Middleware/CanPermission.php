<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\Response;

use App\Models\User;

/**
 * Permission user authorization, validate request
 * @param \Illuminate\Http\Request  $request
 * @param Closure $next The next middleware.
 *
 * @return mixed Valid Permission, authorized or unauthorized user.
 */

class CanPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = new User();
        $canPermission = $user->canAccess($permission);

        if(!$canPermission){
            return Response::UNAUTHORIZED();
        }

        return $next($request);

    }
}
