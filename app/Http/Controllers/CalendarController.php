<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarEventStoreRequest;
use App\Http\Requests\CalendarIndexRequest;
use App\Models\CalendarEntry;
use App\Models\CalendarEvent;
use App\Models\Task;
use App\Services\Calendar\traits\Create;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class CalendarController extends Controller
{
    use Create;

    /**
     * Return a listing of calendar events.
     */
    #[OpenApi\Operation(tags: ['calendar'], method: 'GET')]
    public function index(CalendarIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $display_type = $request->string('display_type', 'month');
        $request->mergeIfMissing($this->getDefaultDays($display_type, $request->date('start') ?? now()));
        $start = $request->date('start');
        $end = $request->date('end');

        $event_type = strval($request->string('event_type', 'all'));
        // $users = $request->input('user',[$request->user()->id]);
        [$tasks, $calendarEvents, $records] = [[], [], []];
        // Log::debug('calendar index', ['start' => $start, 'end' => $end, 'event_type'=>$event_type, 'users' => $users]);
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
            // ->sort(function ($a, $b) {
            //     $a_date = $a instanceof Task ? $a->due : $a->start ?? $a->end;
            //     $b_date = $b instanceof Task ? $b->due : $b->start ?? $b->end;
            //     return $a_date <=> $b_date;
            // });
        // Log::debug("CallenderController::index, all events",[$events->values()]);
        return response()->json($events->values());
    }

    public function show(int $id)
    {
        return CalendarEvent::find($id);
    }

    public function store(CalendarEventStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $calendarEvent = $this->create($request);

        return response()->json($calendarEvent);
    }

    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $calendarEvent->update($request->all());

        return response()->json($calendarEvent);
    }

    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();

        return response()->json(null, 204);
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
        // Log::debug('calendar getDefaultDays', ['display_type' => $display_type, 'start' => $start, 'end' => $end]);
        return ['start' => $start, 'end' => $end];
    }
}
