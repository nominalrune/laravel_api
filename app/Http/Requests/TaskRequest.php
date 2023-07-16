<?php

namespace App\Http\Requests;
use App\Rules\Subtask;

class TaskRequest extends Request
{

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }
    protected function getRules() {
        return  [
            'range' => ['nullable', 'string', 'in:all,shared,mine'],
            'word' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'integer', 'max:255'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
    protected function storeRules() {
        return array_merge(
            $this->required(['title']),
        );
    }
    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
                return [
                    'range' => ['nullable', 'string', 'in:all,shared,mine'],
                    'word' => ['nullable', 'string', 'max:255'],
                    'state' => ['nullable', 'integer', 'max:255'],
                    'user_id' => ['nullable', 'integer', 'exists:users,id'],
                ];
            case 'POST':
                return array_merge_recursive(
                    $this->columns,
                    $this->required(['title']),
                    $this->nullable(['id', 'subtasks']),
                );
            case 'PUT':
            case 'PATCH':
                return array_merge_recursive(
                    $this->columns,
                    $this->required(['id']),
                    $this->nullable(['title', 'subtasks']),
                );
            default:
                return [];
        }
    }
    protected function updateRules() {
        return array_merge_recursive(
            $this->columns,
            $this->required(['id']),
            $this->nullable(['title', 'subtasks']),
        );
    }
    protected $subtaskKeys = [];
    protected $columns = [
        'id' => ['integer', 'exists:tasks,id'],
        'title' => ['string', 'max:255'],
        'subtasks' => ['array', new Subtask],
        'state' => ['integer', 'max:255'],
        'user_id' => ['nullable', 'integer', 'exists:users,id'],
        'due' => ['nullable', 'date_format:Y-m-d'],
        'description' => ['nullable', 'string', 'max:50000'],
        'parent_task_id' => ['nullable', 'integer', 'exists:tasks,id'],
    ];
}
