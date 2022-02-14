<?php

namespace App\Http\Controllers\RoleController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Role;

class Create extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $role = new Role;

        $role->name = $body['name'];

        $role->save();

        return Response::CREATED(
            message: 'Role created successfully.',
            data: [
                'role' => $role,
            ]
        );
    }
}
