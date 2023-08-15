<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateApiTokenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:100'],
        ];
    }
    public function after() : array
    {
        return [
            function (Validator $validator) {
                if ($this->hasTenOrMoreTokens()) {
                    $validator->errors()->add(
                        'maximum token limit',
                        'You have reached the maximum number of allowed tokens.'
                    );
                }
            }
        ];
    }
    protected function hasTenOrMoreTokens() : bool
    {
        return auth()->user()->tokens_count > 9;
    }
}
