<?php

namespace App\Http\Requests;
use App\Rules\SubtaskRule;

class TaskRequest extends Request
{
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }
    protected function getRules():array {
        return  [
            'range' => ['nullable', 'string', 'in:all,shared,mine'],
            'word' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'integer', 'max:255'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
    protected function storeRules():array {
        return array_merge(
            $this->required(['title']),
        );
    }
    protected function updateRules():array {
        return array_merge(
            $this->columns,
            $this->required(['id']),
            $this->nullable(['title', 'subtasks']),
        );
    }
    public function __construct(
    ) {
        parent::__construct();
        $this->columns = [
            'id' => ['integer', 'exists:tasks,id'],
            'title' => ['string', 'max:255'],
            'subtasks' => ['array', ],
            'state' => ['integer', 'max:255'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'due' => ['nullable', 'date_format:Y-m-d'],
            'description' => ['nullable', 'string', 'max:50000'],
            'parent_task_id' => ['nullable', 'integer', 'exists:tasks,id'],
        ];
    }
    protected $subtaskKeys = [];
    protected array $columns;
}
