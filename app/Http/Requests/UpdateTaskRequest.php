<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['nullable','string','max:50000'],
            'status' => ['required','integer'],
            'owner_id' => [ 'integer', 'exists:users,id', 'nullable' ],
            'parent_task_id'=>[ 'integer', 'exists:tasks,id', 'nullable' ],
        ];
    }
}
