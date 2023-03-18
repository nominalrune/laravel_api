<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\Task;
use App\Http\Requests\CalendarIndexRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
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
        return ['start'=>$start, 'end'=>$end];
    }
    public function index(CalendarIndexRequest $request)
    {
        $display_type = $request->string('display_type', 'month');
        $request->mergeIfMissing($this->getDefaultDays($display_type, $request->date('start')??now()));
        $start = $request->date('start');
        $end = $request->date('end');

        $event_type = strval($request->string('event_type', 'all'));
        $users = $request->input('user',[$request->user()->id]);
        list($tasks, $calendarEvents, $records) = [[], [], []];
        // Log::debug('calendar index', ['start' => $start, 'end' => $end, 'event_type'=>$event_type, 'users' => $users]);
        if ($event_type === 'all' || $event_type === 'task') {
                $tasks = $request->user()->tasks()
                    ->where('due', '>=', $start->toDateString())
                    ->where('due', '<=', $end->toDateString())
                    ->get();
        }
        if($event_type === 'all' || $event_type === 'calendar') {
                $calendarEvents = $request->user()->calendarEvents()
                    ->where('end_at', '>=', $start->toDateTimeString())
                    ->where('start_at', '<=', $end->toDateTimeString())
                    ->get();
        }
        if($event_type === 'all' || $event_type === 'record') {
                $records = $request->user()->records()
                    ->where('date', '>=', $start->toDateTimeString())
                    ->where('date', '<=', $end->toDateTimeString())
                    ->get();
        }
        // Log::debug("CallenderController::index, tasks, calendarEvents, records",[$tasks, $calendarEvents, $records]);
        $events = $tasks->concat($calendarEvents)->concat($records)
            ->sort(function ($a, $b) {
                $a_date = $a instanceof Task ? $a->due : $a->start ?? $a->end;
                $b_date = $b instanceof Task ? $b->due : $b->start ?? $b->end;
                return $a_date <=> $b_date;
            });
        Log::debug("CallenderController::index, all events",[$events]);
        return response()->json($events);
    }

    public function show(int $id)
    {
        // Log::debug('calendar show', ['id' => $id]);
        return CalendarEvent::find($id);
    }
    public function store(Request $request)
    {
        $title=$request->string('title');
        $description=$request->string('description');
        $start_at=$request->date('start_at', 'Y-m-d\TH:i', 'Asia/Tokyo');
        $end_at=$request->date('end_at', 'Y-m-d\TH:i', 'Asia/Tokyo');
        $user_id=$request->integer('user_id');
        $calendarEvent = CalendarEvent::create([
            'title' => $title,
            'description' => $description,
            'start_at' => $start_at,
            'end_at' => $end_at,
            'user_id' => $user_id,
        ]);
        return $calendarEvent;
    }
    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $calendarEvent->update($request->all());
        return $calendarEvent;
    }
    public function delete(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();
        return response()->json(null, 204);
    }
}
