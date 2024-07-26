<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

readonly class UnusedPassword implements ValidationRule
{
    public function __construct(
        private ?User $user = null,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(is_null($this->user)) {
            return;
        }

        if ($this->user->isNewPasswordPreviouslyUsed($value)) {
            $fail("validation.unused_password")
                ->translate();
        }

    }
}
