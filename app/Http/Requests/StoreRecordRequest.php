<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id'=>auth()->id(),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description'=>['nullable','string','max:50000'],
            'topic_type'=>['nullable','string','max:255'],
            'topic_id'=>['nullable','integer'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'time'=>['nullable','integer'],
            'user_id'=>['required','integer','exists:users,id'],
        ];
    }
}
