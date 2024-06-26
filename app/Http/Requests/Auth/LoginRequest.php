<?php

namespace App\Http\Requests\Auth;

use App\Enums\Role;
use App\Rules\ValidPhone;
use Exception;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array<int, ValidPhone|Rule|string>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', new ValidPhone],
            'password' => ['required', 'string'],
            'role' => ['required', 'string', Rule::enum(Role::class)],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     */
    public function authenticate(): void
    {
        // $this->ensureIsNotRateLimited();

        /** @var string $role */
        $role = $this->validated('role');

        $role = Role::tryFrom($role);

        if (! $role) {
            throw new Exception('Invalid Role');
        }

        request()->role = $role;

        $data = $this->only('phone', 'password');

        if (! $role->isCustomer()) {
            $data['role'] = $role->userRole();
        }

        $loginAttempt = auth()->guard($role->loginGuard())->attempt($data);

        if (! $loginAttempt) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'phone' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'phone' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(($this->input('phone')).'|'.$this->ip());
    }
}
