<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarEventRequest;
use App\Models\CalendarEntry;
use App\Models\CalendarEvent;
use App\Models\Permission;
use App\Models\Task;
use App\Services\Calendar\traits\Create;
use App\Services\PermissionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    use Create;

    /**
     * Return a listing of calendar events.
     */
    public function index(CalendarEventRequest $request): \Illuminate\Http\JsonResponse
    {
        $display_type = $request->string('display_type', 'month');
        $request->mergeIfMissing($this->getDefaultDays($display_type, $request->date('start') ?? now()));
        $start = $request->date('start');
        $end = $request->date('end');

        $event_type = strval($request->string('event_type', 'all'));
        [$tasks, $calendarEvents, $records] = [[], [], []];
        if ($event_type === 'all' || $event_type === 'task') {
                $tasks = $request->user()->tasks()
                    ->where('due', '>=', $start->toDateString())
                    ->where('due', '<=', $end->toDateString())
                    ->get()->map(function ($task) {
                        return CalendarEntry::fromTask($task);
                    });
        }
        if ($event_type === 'all' || $event_type === 'calendar') {
                $calendarEvents = $request->user()->calendarEvents()
                    ->where('end_at', '>=', $start->toDateTimeString())
                    ->where('start_at', '<=', $end->toDateTimeString())
                    ->get()->map(function ($event) {
                        return CalendarEntry::fromCalendarEvent($event);
                    });
        }
        if ($event_type === 'all' || $event_type === 'record') {
                $records = $request->user()->records()
                    ->where('date', '>=', $start->toDateTimeString())
                    ->where('date', '<=', $end->toDateTimeString())
                    ->get()->map(function ($record) {
                        return CalendarEntry::fromRecord($record);
                    });
        }
        $events = $calendarEvents->concat($tasks)->concat($records);
        return response()->json($events->values());
    }

    public function store(CalendarEventRequest $request): \Illuminate\Http\JsonResponse
    {
        $calendarEvent = $this->create($request);
        PermissionService::setOwnerShip($request->user(), $calendarEvent);
        return response()->json($calendarEvent);
    }

    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        if (! PermissionService::can($request->user(), Permission::UPDATE, $calendarEvent)) {
        $calendarEvent->update($request->all());
        }
        return response()->json($calendarEvent);
    }

    public function destroy(Request $request, CalendarEvent $calendarEvent)
    {
        if (! PermissionService::can($request->user(), Permission::DELETE, $calendarEvent)) {
        abort(404);
    }
        $calendarEvent->delete();
        return response(status: 204);
    }

    private function getDefaultDays($display_type, Carbon $date)
    {
        switch ($display_type) {
            case 'month':
                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();
                break;
            case 'week':
                $start = $date->copy()->startOfWeek();
                $end = $date->copy()->endOfWeek();
                break;
            case 'day':
                $start = $date->copy()->startOfDay();
                $end = $date->copy()->endOfDay();
                break;
        }
        return ['start' => $start, 'end' => $end];
    }
}
