<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }
    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
                return [
                    'word' => ['nullable', 'string', 'max:255'],
                    'state' => ['nullable', 'integer', 'max:255'],
                    'user_id' => ['nullable', 'integer', 'exists:users,id'],
                ];
            case 'POST':
                return array_merge(
                    $this->common,
                    $this->required(['title',  'subtasks']),
                    $this->nullable(['id',]),
                );
            case 'PUT':
            case 'PATCH':
                return array_merge(
                    $this->required(['id']),
                    $this->nullable(['title', 'subtasks']),
                );
            default:
                return [];
        }
    }
    protected $columns = [
        'id' => ['integer', 'exists:tasks,id'],
        'title' => ['string', 'max:255'],
        'subtasks' => ['array'],
        'state' => ['integer', 'max:255'],
        'user_id' => ['nullable', 'integer', 'exists:users,id'],
        'due' => ['nullable', 'date_format:Y-m-d'],
        'description' => ['nullable', 'string', 'max:50000'],
        'parent_task_id' => ['nullable', 'integer', 'exists:tasks,id'],
        'subtasks.*' => ['nullable', 'array'],
        'subtasks.*.title' => ['required', 'string', 'max:255'],
        'subtasks.*.state' => ['required', 'integer', 'max:255'],
        'subtasks.*.subtasks' => ['required', 'array'],
        'subtasks.*.subtasks.*' => ['nullable', 'array'],
        'subtasks.*.subtasks.*.title' => ['required', 'string', 'max:255'],
        'subtasks.*.subtasks.*.state' => ['required', 'integer', 'max:255'],
        'subtasks.*.subtasks.*.subtasks' => ['required', 'array'],
    ];
    private function nullable(array $keys)
    {
        return array_map(fn($key) => [$key => [...$this->columns[$key], 'nullable']], $keys);
    }
    private function required(array $keys)
    {
        return array_map(fn($key) => [$key => [...$this->columns[$key], 'required']], $keys);
    }
}
