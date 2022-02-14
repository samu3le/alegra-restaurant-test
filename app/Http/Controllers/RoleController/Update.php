<?php

namespace App\Http\Controllers\RoleController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Response;

use App\Models\Role;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $role = Role::find($body['id']);

        if(isset($body['name'])) {
            $role->name = $body['name'];
        }
        if(isset($body['is_active'])) {
            $role->is_active = $body['is_active'];
        }

        $role->save();

        return Response::OK(
            message: 'Role updated successfully.',
            data: [
                'role' => $role,
            ],
        );
    }
}
