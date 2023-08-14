<?php

namespace App\Http\Requests;

class RecordRequest extends Request
{
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }
    protected function getRules():array
    {
        return [
            'word' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'integer', 'max:255'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'], // だれかほかの人の記録を指定する
            'mine' => ['nullable', 'string', 'in:all,shared,mine'],
        ];
    }
    protected function storeRules():array
    {
        return [
            ...$this->required([
                'title',
                'user_id',
                'date',
            ]),
            ...$this->nullable([
                'recordable_type',
                'recordable_id',
                'description',
                'time'
            ]),
        ];
    }
    protected function updateRules():array
    {
        return [
            ...$this->nullable(['id', 'title', 'user_id', 'date', 'time', 'description', 'recordable_type', 'recordable_id']),
        ];
    }
    protected array $columns = [
        'id' => ['integer', 'exists:records,id'],
        'title' => ['string', 'max:255'],
        'user_id' => ['integer', 'exists:users,id'],
        'date' => ['date_format:Y-m-d'],
        'time' => ['integer', 'max:1000'],
        'description' => ['string', 'max:50000'],
        'recordable_type' => ['string', 'max:255'],
        'recordable_id' => ['integer'],
    ];
}
