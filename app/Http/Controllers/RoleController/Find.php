<?php

namespace App\Http\Controllers\RoleController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Role;

class Find extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $role = Role::find($query['id']);

        return Response::OK(
            message: 'Role found successfully.',
            data: [
                'role' => $role,
            ]
        );
    }
}
