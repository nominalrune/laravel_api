<?php

namespace App\Services\Calendar\traits;

use App\Models\CalendarEvent;
use App\Http\Requests\CalendarEventStoreRequest;

trait Create
{
    /**
     * parse inputs from the request
     */
    private function parseInputs(CalendarEventStoreRequest $request): array
    {
        return [
            'title' => $request->string('title'),
            'description' => $request->string('description'),
            'start_at' => $request->date('start_at', 'Y-m-d\TH:i:s.v\Z', 'Asia/Tokyo'),
            'end_at' => $request->date('end_at', 'Y-m-d\TH:i:s.v\Z', 'Asia/Tokyo'),
            'user_id' => $request->user()->id,
        ];
    }
    /**
     * create a calendar event
     */
    protected function create(CalendarEventStoreRequest $request): CalendarEvent
    {
        $inputs = $this->parseInputs($request);
        $calendarEvent = CalendarEvent::create($inputs);
        return $calendarEvent;
    }
}
