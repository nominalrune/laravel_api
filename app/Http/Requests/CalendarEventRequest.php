<?php

namespace App\Http\Requests;

class CalendarEventRequest extends Request
{
    protected function getRules(): array
    {
        return [
            'display_type' => ['nullable', 'in:month,week,day'],
            'start' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'end' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'event_type' => ['nullable', 'string'],
            'user.*.id' => ['nullable', 'integer', 'exists:users,id'],
            'status_type' => ['nullable', 'string'],
        ];
    }
    protected function storeRules(): array
    {
        return [
            ...$this->required(['title', 'start_at', 'end_at']),
            ...$this->nullable(['description']),
        ];
    }
    protected function updateRules(): array
    {
        return [
            ...$this->nullable(['title', 'description', 'start_at', 'end_at']),
        ];
    }
    protected array $columns = [
        'id' => ['integer', 'exists:calendars,id'],
        'title' => ['string', 'min:1','max:255'],
        'description' => ['string', 'max:5000'],
        'start_at' => ['date_format:Y-m-d\TH:i:s.v\Z'],
        'end_at' => ['date_format:Y-m-d\TH:i:s.v\Z'],
    ];
}
