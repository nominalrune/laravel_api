<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string|null $due
 * @property string|null $description
 * @property int|null $user_id
 * @property int $state
 * @property int|null $parent_task_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Task> $childTasks
 * @property-read int|null $child_tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User|null $user
 * @property-read Task|null $parentTask
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'due',
        'description',
        'user_id',
        'state',
        'parent_task_id',
        'subtasks',
    ];

    protected $appends = [
        'url',
    ];

    protected $casts = [
        'due' => 'datetime:Y-m-d',
        'subtasks' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->morphMany(Permission::class, 'permissionable');
    }

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
