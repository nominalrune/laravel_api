<?php
namespace App\Models;

use stdClass;

class CalendarEntry
{
    public $id;
    public $allDay;
    public $title;
    public $start;
    public $end;
    // public $url;
    public ExtendedProps $extendedProps;
    public static function fromTask(Task $task):CalendarEntry{
        $entry=new CalendarEntry();
        $entry->id="task-".strval($task->id);
        $entry->title=$task->title;
        $entry->allDay=true;
        $entry->start=$task->due;
        // $entry->url=route('task.show', $task->id);
        $entry->extendedProps=new ExtendedTaskProps($task);
        return $entry;
    }
    public static function fromRecord(Record $record):CalendarEntry{
        $entry=new CalendarEntry();
        $entry->id="record-".strval($record->id);
        $entry->title=$record->task->title;
        $entry->allDay=true;
        $entry->start=$record->date;
        // $entry->url=route('record.show', $record->id);
        $entry->extendedProps=new ExtendedRecordProps($record);
        return $entry;
    }
    public static function fromCalendarEvent(CalendarEvent $event):CalendarEntry{
        $entry=new CalendarEntry();
        $entry->id="event-".strval($event->id);
        $entry->title=$event->title;
        $entry->allDay=false;
        $entry->start=$event->start_at;
        $entry->end=$event->end_at;
        // $entry->url=$event->url;
        $entry->extendedProps=new ExtendedCalendarEventProps($event);
        return $entry;
    }
    public function toArray(){
        return [
            'id'=>$this->id,
            'allDay'=>$this->allDay,
            'title'=>$this->title,
            'start'=>$this->start,
            'end'=>$this->end,
            // 'url'=>$this->url,
            'extendedProps'=>$this->extendedProps->toArray(),
        ];
    }
}


abstract class ExtendedProps extends stdClass{
    public $title;
    public $entry_type;
    public $entry_id;
    public $user_id;
    public $description;
    public $created_at;
    public $updated_at;
    public function __construct($entry)
    {
        $this->title=$entry->title;
        $this->entry_id=$entry->id;
        $this->user_id=$entry->user_id;
        $this->description=$entry->description;
        $this->created_at=$entry->created_at;
        $this->updated_at=$entry->updated_at;
    }
    abstract public function toArray():array;
}
class ExtendedTaskProps extends ExtendedProps{
    public $entry_type=Task::class;
    public $due;
    public $task_type;
    public $status;
    public $parent_task_id;
    public $parentTask;
    public function __construct(Task $task)
    {
        parent::__construct($task);
        $this->due=$task->due;
        $this->task_type=$task->task_type;
        $this->status=$task->status;
        $this->parent_task_id=$task->parent_task_id;
        $this->parentTask=$task->parentTask;
    }
    public function toArray():array{
        return [
            'title'=>$this->title,
            'entry_type'=>$this->entry_type,
            'entry_id'=>$this->entry_id,
            'user_id'=>$this->user_id,
            'description'=>$this->description,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'due'=>$this->due,
            'task_type'=>$this->task_type,
            'status'=>$this->status,
            'parent_task_id'=>$this->parent_task_id,
            'parentTask'=>$this->parentTask,
        ];
    }
}
class ExtendedRecordProps extends ExtendedProps{
    public $entry_type=Record::class;
    public $date;
    public $time;
    public $task_id;
    public $task;
    public function __construct(Record $record)
    {
        parent::__construct($record);
        $this->date=$record->date;
        $this->time=$record->time;
        $this->task_id=$record->task_id;
        $this->task=$record->task;
    }
    public function toArray():array{
        return [
            'title'=>$this->title,
            'entry_type'=>$this->entry_type,
            'entry_id'=>$this->entry_id,
            'user_id'=>$this->user_id,
            'description'=>$this->description,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'date'=>$this->date,
            'time'=>$this->time,
            'task_id'=>$this->task_id,
            'task'=>$this->task,
        ];
    }
}
class ExtendedCalendarEventProps extends ExtendedProps{
    public $entry_type=CalendarEvent::class;
    public $status;
    public $start_at;
    public $end_at;
    public function __construct(CalendarEvent $event)
    {
        parent::__construct($event);
        $this->status=$event->status;
        $this->start_at=$event->start_at;
        $this->end_at=$event->end_at;
    }
    public function toArray():array{
        return [
            'title'=>$this->title,
            'entry_type'=>$this->entry_type,
            'entry_id'=>$this->entry_id,
            'user_id'=>$this->user_id,
            'description'=>$this->description,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'status'=>$this->status,
            'start_at'=>$this->start_at,
            'end_at'=>$this->end_at,
        ];
    }
}
