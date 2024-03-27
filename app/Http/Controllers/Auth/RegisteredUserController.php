<?php

namespace App\Http\Controllers\Auth;

use App\DTO\EmployeeDTO;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Rules\ValidPhone;
use App\Services\InteraktService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, InteraktService $service): JsonResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'nullable', new ValidPhone, 'unique:' . User::class],
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'role' => UserRole::EMPLOYEE,
        ]);

        $res = $service->sendNewAccountCreatedMessageToEmployee(
            EmployeeDTO::fromModel($user)
        );

        return $this->response(
            data: [
                'user' => UserResource::make($user),
                'res' => $res->body()
            ],
            message: 'New Employee Created',
        );
    }
}
