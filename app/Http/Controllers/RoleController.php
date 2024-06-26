<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->response(
            data: [
                'roles' => Role::labels(),
            ],
            message: 'List of Roles'
        );
    }
}
