<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'due' => ['nullable', 'date_format:Y-m-d'],
            'description' => ['nullable', 'string', 'max:50000'],
            'status' => ['nullable', 'integer'],
            'parent_task_id' => ['integer', 'exists:tasks,id', 'nullable'],
            'subtasks' => ['nullable', 'array'],
            'subtasks.*' => ['nullable', 'array'],
            'subtasks.*.title' => ['required', 'string', 'max:255'],
            'subtasks.*.status' => ['required', 'integer', 'max:255'],
        ];
    }
}
