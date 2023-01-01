<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'name'
    ];
    public function userGroupMembers()
    {
        $this->hasMany(UserGroupMember::class, 'user_group_id', 'id');
    }
    public function users()
    {
        $this->hasManyThrough(User::class, UserGroupMember::class, 'user_group_id', 'id', 'id', 'user_id');
    }
    public function acls()
    {
        return $this->hasMany(Acl::class, 'target_id', 'id')->where('target_table', 'user_groups');
    }
    public function acl(User $user)
    {
        return $this->acls()->where('user_id', $user->id)->first();
    }
}
