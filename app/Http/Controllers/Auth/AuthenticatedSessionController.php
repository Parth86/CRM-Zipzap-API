<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    private Role $role;

    private User|Customer $user;

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        // $request->session()->regenerate();

        $this->role = request()->role;

        $this->user = auth()->guard($this->role->loginGuard())->user();

        $token = $this->user->createToken('token')->plainTextToken;

        return $this->response(
            data: [
                'user' => $this->role->toResource($this->user),
                'token' => $token
            ],
            message: 'Login Successful'
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $this->user = auth()->user();
        $this->user->tokens()->delete();

        Auth::guard('web')->logout();
        Auth::guard('customers')->logout();

        return $this->response(
            data: [],
            message: 'User Loggged out'
        );
    }
}
