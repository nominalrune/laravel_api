<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class Subtask implements ValidationRule
{
    use ValidatesAttributes;
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (is_null($value))
            return;
        if (! $this->validateArray($attribute, $value))
            $fail($attribute . ' must be an array.');
        if (! $this->validateString($attribute, $value))
            $fail($attribute . ' must be a string.');
        if (! $this->validateInteger($attribute, $value))
            $fail($attribute . ' must be an integer.');
        if (! $this->validateIn($attribute, $value, [0, 1, 2, 3, 4]))
            $fail($attribute . ' must be 0, 1, 2, 3, or 4.');

        if (isset($value['subtasks'])) {
            $this->validate($attribute . '.subtasks', $value['subtasks'], $fail);
        }
    }
}
