<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelAcl extends Model
{
    use HasFactory;
    protected $fillable = [
        'target_table',
        'user_group_id',
        'read',
        'create',
        'update',
        'delete',
        'share'
    ];
    public static function modelAcl(string $table_name, UserGroup $userGroup){
        return ModelAcl::where('target_table', $table_name)
        ->where('user_group_id', $userGroup->id)->get()->first();
    }
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
