<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Rules\ValidPhone;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        $customer = $this->route('customer');
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'unique:' . Customer::class . ",email,{$customer->id}"],
            'phone' => [
                'required',
                'numeric',
                new ValidPhone,
                'unique:' . Customer::class . ",phone,{$customer->id}",
            ],
            'alert_phone' => [
                'sometimes',
                'nullable',
                'numeric',
                new ValidPhone,
                'unique:' . Customer::class . ",alert_phone,{$customer->id}",
            ],
            'address' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
