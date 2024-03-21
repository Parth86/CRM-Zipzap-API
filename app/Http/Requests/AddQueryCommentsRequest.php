<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use App\Models\QueryComment;

/**
 * @mixin QueryComment
 */
class AddQueryCommentsRequest extends FormRequest
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
            'comments' => ['required', 'string'],
            'photo' => ['sometimes', 'nullable', File::types(['png', 'jpg', 'jpeg', 'pdf'])->max(6000)],
        ];
    }
}
