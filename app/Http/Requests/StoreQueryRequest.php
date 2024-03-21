<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Query;
use Illuminate\Validation\Rules\File;

/**
 * @mixin Query
 */
class StoreQueryRequest extends FormRequest
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
            // 'customer_id' => ['required', 'exists:' . Customer::class . ',uuid'],
            'product' => ['required', 'string'],
            'comments' => ['required', 'string'],
            'photo' => ['sometimes', 'nullable', File::types(['png', 'jpg', 'jpeg', 'pdf'])->max(6000)],
        ];
    }
}
