<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\User;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = [
        'target_table',
        'target_id',
        'permission_type',
        'user_id',
    ];

    public function target()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
