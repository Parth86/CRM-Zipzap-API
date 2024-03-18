<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $MIN_PHONE = 1_000_000_000;

        $MAX_PHONE = 9_999_999_999;

        if (! is_numeric($value) or $value < $MIN_PHONE or $value > $MAX_PHONE) {
            $fail('The :attribute must be a valid phone number.');
        }
    }
}
