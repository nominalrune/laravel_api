<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroupMember extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_group_id',
        'user_id',
    ];
    public function userGroup()
    {
        $this->belongsTo(UserGroup::class, 'user_group_id', 'id');
    }
    public function user()
    {
        $this->belongsTo(User::class, 'user_id', 'id');
    }
}
