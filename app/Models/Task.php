<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
/**
 * App\Models\Task
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $type
 * @property string $title
 * @property string|null $due
 * @property string|null $description
 * @property int|null $owner_id
 * @property int $status
 * @property int|null $parent_task_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Task> $childTasks
 * @property-read int|null $child_tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User|null $owner
 * @property-read Task|null $parentTask
 */
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
    protected $appends = [
        'url'
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
    // public function permissions()
    // {
    //     return $this->morphMany(Permission::class, 'target');
    // }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('task.show', $this->id),
        );
    }

}
