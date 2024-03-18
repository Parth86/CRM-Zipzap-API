<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Rules\ValidPhone;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'unique:'.Customer::class],
            'phone' => [
                'required',
                'numeric',
                new ValidPhone,
                'unique:'.Customer::class,
            ],
            'alert_phone' => [
                'sometimes',
                'nullable',
                'numeric',
                new ValidPhone,
                'unique:'.Customer::class,
            ],
            'address' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
