<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
                    'user_id' => ['nullable', 'integer', 'exists:users,id'], // だれかほかの人の記録を指定する
                    'mine' => ['nullable', 'string', 'in:all,shared,mine'],
                ];
            case 'POST':
                return array_merge_recursive(
                    $this->required(['title', 'date', 'time']),
                    $this->nullable([ 'description', 'recordable_type', 'recordable_id']),
                );
            case 'PUT':
            case 'PATCH':
                return array_merge_recursive(
                    $this->required(['id']),
                    $this->nullable(['title', 'user_id', 'date', 'time', 'description', 'recordable_type', 'recordable_id']),
                );
            default:
                return [];
        }
    }

    protected $columns = [
        'id' => ['integer', 'exists:tasks,id'],
        'title' => ['string', 'max:255'],
        'user_id' => ['integer', 'exists:users,id'],
        'date' => ['date_format:Y-m-d'],
        'time' => ['integer', 'max:1000'],
        'description' => ['string', 'max:50000'],
        'recordable_type' => ['string', 'max:255'],
        'recordable_id' => ['integer'],
    ];

    private function nullable(array $keys)
    {
        return array_reduce($keys,fn ($acc, $curr) => [ ...$acc, $curr => [...$this->columns[$curr],'nullable']],[]);
    }

    private function required(array $keys)
    {
        return  array_reduce($keys,fn ($acc, $curr) => [ ...$acc, $curr => [...$this->columns[$curr],'required']],[]);
    }
}
