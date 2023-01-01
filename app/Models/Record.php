<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UserGroup;
use App\Models\ModelAcl;

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
    public function acls()
    {
        return $this->hasMany(Acl::class, 'target_id', 'id')->where('target_table', 'records');
    }
    public function acl(User $user)
    {
        return $this->acls()->where('user_group_id', $user->userGroup->id)->first();
    }
}
