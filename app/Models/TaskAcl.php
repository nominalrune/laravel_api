<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\User;

class TaskAcl extends Model
{
    use HasFactory;
    protected $fillable = [
        'target_id',
        'user_id',
        'create',
        'update',
        'delete',
        'share'
    ];



    public function target()
    {
        return $this->belongsTo(Task::class,'target_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
