<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeIndexResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function index(): JsonResponse
    {
        $employees = User::query()->isEmployee()->get();

        return $this->response(
            data: [
                'employees' => EmployeeIndexResource::collection($employees),
            ],
            message: 'List of Employees'
        );
    }
}
