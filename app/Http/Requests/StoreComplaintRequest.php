<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreComplaintRequest extends FormRequest
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
            'customer_id' => ['required', 'string', 'exists:'.Customer::class.',uuid'],
            'product' => ['required', 'string'],
            'comments' => ['required', 'string'],
            'photo' => ['nullable', 'sometimes',  File::types(['png', 'jpg', 'jpeg', 'pdf'])->max(8000)],
        ];
    }
}
