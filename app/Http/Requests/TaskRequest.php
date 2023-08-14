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
            $this->nullable(['subtasks', 'state', 'user_id', 'due', 'description', 'parent_task_id']),
        );
    }
    protected function updateRules():array {
        return array_merge(
            $this->required(['id']),
            $this->nullable(['title', 'subtasks', 'state', 'user_id', 'due', 'description', 'parent_task_id']),
        );
    }
    public function __construct(
    ) {
        parent::__construct();
        $this->columns = [
            'id' => ['integer', 'exists:tasks,id'],
            'title' => ['string', 'min:1', 'max:255'],
            'subtasks' => ['array', ], // validates at controller. see TaskController.php
            'state' => ['integer', 'max:255'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'due' => ['nullable', 'date_format:Y-m-d'],
            'description' => ['nullable', 'string', 'max:50000'],
            'parent_task_id' => ['nullable', 'integer', 'exists:tasks,id'],
        ];
    }
    protected array $columns;
}
