<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordRequest extends FormRequest
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
            case 'GET': // TODO これチェックされるの？ FormRequestって名前からして、GETの時はチェックされないような気がする。urlクエリもね
                return [
                    'word' => ['nullable', 'string', 'max:255'],
                    'state' => ['nullable', 'integer', 'max:255'],
                    'user_id' => ['nullable', 'integer', 'exists:users,id'],// だれかほかの人の記録を指定する
                    'show' =>['nullable', 'string', 'in:all,me,id']
                ];
            case 'POST':
                return array_merge(
                    $this->required(['title','date','time']),
                    $this->nullable(['state','task_id','description']),
                );
            case 'PUT':
            case 'PATCH':
                return array_merge(
                    $this->required(['id']),
                    $this->nullable(['title','state','user_id','task_id','date','time','description']),
                );
            default:
                return [];
        }
    }
    protected $columns = [
        'id' => ['integer', 'exists:tasks,id'],
        'title' => ['string', 'max:255'],
        'state' => ['integer', 'max:255'],
        'user_id' => ['integer', 'exists:users,id'],
        'task_id' => ['integer', 'exists:tasks,id'],
        'date' => ['date_format:Y-m-d'],
        'time' => ['integer', 'max:1000'],
        'description' => ['string', 'max:50000'],
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
