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
    private function switchDefaultAccordingToDisplayType($display_type, Carbon $date)
    {
        $start = null;
        $end = null;
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
        Log::debug('calendar switchDefaultAccordingToDisplayType', ['display_type' => $display_type, 'start' => $start, 'end' => $end]);
        return [$start, $end];
    }
    public function index(CalendarIndexRequest $request)
    {
        $display_type = $request->string('display_type', 'week');
        list($start, $end) = $this->switchDefaultAccordingToDisplayType($display_type, now());
        $start = $request->date('start')?? $start;
        $end = $request->date('end')??$end;

        $event_type = $request->string('event_type', 'all');
        $users = $request->input('user');
        list($tasks, $calendarEvents, $records) = [[], [], []];
        Log::debug('calendar index', ['start' => $start, 'end' => $end]);
        switch ($event_type) {
            case 'all':
                $tasks = $request->user()->tasks()
                    ->where('due', '>=', $start->toDateString())
                    ->where('due', '<=', $end->toDateString())
                    ->get();
                $calendarEvents = $request->user()->calendarEvents()
                    ->where('end_at', '>=', $start->toDateTimeString())
                    ->orWhere('start_at', '<=', $end->toDateTimeString())
                    ->get();
                $records = $request->user()->records()
                    ->where('date', '>=', $start->toDateTimeString())
                    ->orWhere('date', '<=', $end->toDateTimeString())
                    ->get();
                break;
            case 'task':
                $tasks = $request->user()->tasks()
                    ->where('due', '>=', $start->toDateString())
                    ->where('due', '<=', $end->toDateString())
                    ->get();
                break;
            case 'calendar':
                $calendarEvents = $request->user()->calendarEvents()
                    ->where('end', '>=', $start->toDateString())
                    ->orWhere('start', '<=', $end->toDateString())
                    ->get();
                break;
            case 'record':
                $records = $request->user()->records()
                    ->where('date', '>=', $start->toDateTimeString())
                    ->orWhere('date', '<=', $end->toDateTimeString())
                    ->get();
                break;
        }
        Log::debug("CallenderController::index, tasks, calendarEvents, records",[$tasks, $calendarEvents, $records]);
        $events = collect([$tasks, $calendarEvents, $records])->flatten(1)->sort(function ($a, $b) {
            $a_date = $a instanceof Task ? $a->due : $a->start ?? $a->end;
            $b_date = $b instanceof Task ? $b->due : $b->start ?? $b->end;
            return $a_date <=> $b_date;
        })->all();
        Log::debug("CallenderController::index, all events",[$events]);
        return response()->json($events);
    }
    public function show(int $id)
    {
        Log::debug('calendar show', ['id' => $id]);
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
