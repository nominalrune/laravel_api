<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarIndexRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'display_type'=>['nullable','in:month,week,day'],
            'start'=>['nullable','date_format:Y-m-d H:i:s'],
            'end'=>['nullable','date_format:Y-m-d H:i:s'],
            'event_type'=>['nullable','string'],
            'user.*.id'=>['nullable','integer','exists:users,id'],
            'status_type'=>['nullable','string'],
        ];
    }
}
