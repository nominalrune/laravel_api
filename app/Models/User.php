<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    public static function createUserWithUserGroup(array $args){
        $user= User::create($args);
        $group=UserGroup::create(['name'=>$user->name]);
        UserGroupMember::create([
            'user_group_id'=>$group->id,
            'user_id'=>$user->id,
        ]);
        return $user;
    }
    public function userGroupMembers()
    {
        $this->hasMany(UserGroupMember::class, 'user_id', 'id');
    }
    public function userGroups()
    {
        $this->hasManyThrough(UserGroup::class, UserGroupMember::class, 'user_id', 'id', 'id', 'user_group_id');
    }

    /**
     * > This function returns a `UserGroup` model that is associated with the `User` model through the
     * `UserGroupMember` model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough A collection of UserGroup objects.
     */
    public function userGroup()
    {
        return $this->hasOneThrough(UserGroup::class, UserGroupMember::class, 'user_id', 'id', 'id', 'user_group_id')->where('is_individual', true);
    }

    public function acls()
    {
        return $this->hasMany(Acl::class, 'target_id', 'id')->where('target_table', 'users');
    }
    public function acl(User $user)
    {
        return $this->acls()->where('user_group_id', $user->userGroup->id)->first();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'icon_url'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
