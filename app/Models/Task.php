<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Task extends Model
{
    use HasFactory, SoftDeletes, Searchable;
    protected $fillable=[
        'title',
        'description',
        'assigned_to_id',
        'status',
        'parent_task_id',
    ];
    public function searchableAs()
    {
        return 'tasks_index';
    }
    public function assignedTo()
    {
        return $this->belongsTo(UserGroup::class, 'assigned_to_id', 'id');
    }
    public function isAssignedTo(User $user)
    {
        return $this->assignedTo->users()->where('id', $user->id)->exists();
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
        return $this->hasMany(Acl::class, 'target_id', 'id')->where('target_table', 'tasks');
    }
    public function acl(User $user)
    {
        return $this->acls()->where('user_group_id', $user->userGroup->id)->first();
    }
}
