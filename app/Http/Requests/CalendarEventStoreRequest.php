<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarEventStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'start_at' => ['required', 'date_format:Y-m-d\TH:i:s.v\Z'],
            'end_at' => ['required', 'date_format:Y-m-d\TH:i:s.v\Z'],
        ];
    }
}
