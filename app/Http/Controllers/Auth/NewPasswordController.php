<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewPasswordController extends Controller
{
    private User|Customer $user;

    public function __construct()
    {
        $user = auth()->user();

        if (! $user) {
            throw new Exception('Auth Failed');
        }

        $this->user = $user;
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        /** @var string $old_password */
        $old_password = $request->old_password;

        if (! Hash::check($old_password, $this->user->password)) {
            return $this->response([
                'errors' => [
                    'old_password' => 'Old Password is incorrect',
                ],
            ], 'Old Password is incorrect', false, 400);
        }

        /** @var string $password */
        $password = $request->password;
        $this->user->update([
            'password' => Hash::make($password),
        ]);

        return $this->response([], 'Password Changed Successfully', true);
    }

    public function change(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        /** @var string $password */
        $password = $request->password;

        $this->user->update([
            'password' => Hash::make($password),
        ]);

        return $this->response([], 'Password Changed Successfully', true);
    }
}
