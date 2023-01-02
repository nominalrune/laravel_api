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

    /**
     * returns the userGroupMembers of this userGroup
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<UserGroupMember>
     */
    public function userGroupMembers()
    {
        $this->hasMany(UserGroupMember::class, 'user_group_id', 'id');
    }
    public function users()
    {
        $this->hasManyThrough(User::class, UserGroupMember::class, 'user_group_id', 'id', 'id', 'user_id');
    }
    /**
     * > This function returns all the ACLs that are associated with this user group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Acl> A collection of Acl objects that belong to the UserGroup object.
     */
    public function acls()
    {
        return $this->hasMany(Acl::class, 'target_id', 'id')->where('target_table', 'user_groups');
    }

    /**
     * Return the first ACL record for the given user.
     *
     * @param User user The user object that you want to check the ACL for.
     *
     * @return  The first ACL record for the user.
     */
    public function acl(User $user)
    {
        return $this->acls()->where('user_id', $user->id)->first();
    }
}
