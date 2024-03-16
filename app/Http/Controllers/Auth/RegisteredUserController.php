<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Rules\ValidPhone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'nullable', new ValidPhone, 'unique:' . User::class],
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'role' => UserRole::EMPLOYEE
        ]);

        return $this->response(
            data: [
                'user' => UserResource::make($user)
            ],
            message: "New Employee Created",
        );
    }
}
