<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ChileanNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string = null): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!preg_match("/^(\+?56)?(\s?)(0?9)(\s?)[98765432]\d{7}$/", $value)) {
            $fail("The :attribute is not a valid mobile chilean phone number.");
        }
    }
}
