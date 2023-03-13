<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    protected $fillable=[
        'type',
        'title',
        'due',
        'description',
        'owner_id',
        'status',
        'parent_task_id',
    ];
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id', 'id');
    }
    public function childTasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id', 'id');
    }
    public function permissions()
    {
        return $this->morphMany(Permission::class, 'target');
    }

}
