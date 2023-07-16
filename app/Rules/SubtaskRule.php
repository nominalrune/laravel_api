<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class SubtaskRule implements ValidationRule
{
    use ValidatesAttributes;
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (is_null($value))
            return;
        if (! $this->validateArray($attribute, $value))
            $fail($attribute . ' must be an array.');
        if (! $this->validateKey('title', $value['title']))
            $fail($attribute . ' must have a title.');
        if (! $this->validateString($attribute, $value['title']))
            $fail($attribute . ' must be a string.');
        if (! $this->validateKey('state', $value['state']))
            $fail($attribute . ' must have a state.');
        if (! $this->validateInteger($attribute, $value['state']))
            $fail($attribute . ' must be an integer.');
        if (! $this->validateIn($attribute, $value['state'], [0, 1, 2, 3, 4]))
            $fail($attribute . ' must be 0, 1, 2, 3, or 4.');

        if (isset($value['subtasks'])) {
            $this->validate($attribute . '.subtasks', $value['subtasks'], $fail);
        }
    }
    protected function validateKey(string $key, array $array) : bool
    {
        return array_key_exists($key, $array);
    }
}
