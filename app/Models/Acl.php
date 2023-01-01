<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acl extends Model
{
    use HasFactory;
    protected $fillable = [
        'target_table',
        'target_id',
        'user_group_id',
        'read',
        'create',
        'update',
        'delete',
        'share'
    ];

    public function target()
    {
        return $this->belongsTo($this->target_table);
    }
    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function acls()
    {
        return $this->hasMany(Acl::class, 'target_id', 'id')->where('target_table', 'acls');
    }
    public function acl(User $user)
    {
        return $this->acls()->where('user_group_id', $user->userGroup->id)->first();
    }
}
