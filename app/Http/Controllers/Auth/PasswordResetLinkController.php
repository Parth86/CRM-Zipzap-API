<?php

namespace App\Http\Controllers\Auth;

use App\DTO\UserDTO;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Services\InteraktService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Str;

class PasswordResetLinkController extends Controller
{
    private User|Customer $user;

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, InteraktService $service): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'role' => ['required', 'string', Rule::enum(Role::class)],
        ]);

        /** @var string $role */
        $role = $request->role;

        $role = Role::tryFrom($role);

        try {
            if ($role == Role::ADMIN or $role == Role::EMPLOYEE) {
                $this->user = User::query()->where('phone', $request->phone)->firstOrFail();
            } elseif ($role == Role::CUSTOMER) {
                $this->user = Customer::query()->where('phone', $request->phone)->firstOrFail();
            }
        } catch (\Throwable $th) {
            return $this->response(
                [],
                'No account registered with this phone number for this role',
                false,
                400
            );
        }

        $otp = Str::random(6);

        $this->user->update([
            'otp' => $otp,
        ]);

        $user = UserDTO::fromModel($this->user, $otp);

        $res = $service->sendOTPMessage(
            $user
        );

        return $this->response(
            [
                'res' => $res->body(),
            ],
            "OTP sent to your whatsapp number {$user->alert_phone}"
        );
    }

    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'role' => ['required', 'string', Rule::enum(Role::class)],
            'otp' => ['required', 'string'],
        ]);

        /** @var string $role */
        $role = $request->role;

        $role = Role::tryFrom($role);

        try {
            if ($role == Role::ADMIN or $role == Role::EMPLOYEE) {
                $this->user = User::query()->where('phone', $request->phone)->firstOrFail();
            } elseif ($role == Role::CUSTOMER) {
                $this->user = Customer::query()->where('phone', $request->phone)->firstOrFail();
            }
        } catch (\Throwable $th) {
            return $this->response(
                [],
                'No account registered with this phone number for this role',
                false,
                400
            );
        }

        if ($this->user->otp != $request->otp) {
            return $this->response(
                [
                    'errors' => [
                        'otp' => 'Incorrect OTP',
                    ],
                ],
                'Incorrect OTP',
                false,
                400
            );
        }

        return $this->response(
            [
                'token' => $this->user->createToken('token')->plainTextToken,
            ],
            'OTP Correct',
        );
    }
}
