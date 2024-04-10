<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Customer;
use App\Models\User;
use Exception;
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
        // if (!$request->role instanceof Role) {
        //     throw new Exception("Invalid Role");
        // }

        $this->role = request()->role;

        $user = auth()->guard($this->role->loginGuard())->user();

        if ($user == null) {
            throw new Exception('Auth Failed');
        }

        $this->user = $user;

        $token = $this->user->createToken('token')->plainTextToken;

        return $this->response(
            data: [
                'user' => $this->role->toResource($this->user),
                'token' => $token,
            ],
            message: 'Login Successful'
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user instanceof User and !$user instanceof Customer) {
            throw new Exception('Auth Failed');
        }
        $this->user = $user;
        // $this->user->tokens()->delete();
        $this->user->currentAccessToken()->delete();

        Auth::guard('web')->logout();
        Auth::guard('customers')->logout();

        return $this->response(
            data: [],
            message: 'User Loggged out'
        );
    }
}
