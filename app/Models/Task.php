<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'title',
        'description',
        'assigned_to_id',
        'status',
        'parent_task_id',
    ];
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id', 'id');
    }
    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id', 'id');
    }
    public function childTasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id', 'id');
    }
    public function acls()
    {
        return $this->hasMany(TaskAcl::class, 'target_id', 'id');
    }
    public function acl(User $user)
    {
        return $this->acls()->where('user_id', $user->id);
    }
}
