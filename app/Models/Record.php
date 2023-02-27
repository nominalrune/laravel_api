<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'title',
        'description',
        'user_id',
        'related_task_id',
        'started_at',
        'ended_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function relatedTask()
    {
        return $this->belongsTo(Task::class);
    }
    public function permissions()
    {
        return $this->morphMany(Permission::class, 'target', 'target_table', 'target_id');
    }
}
