<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PermissionService
{
    /**
     * @template T of Model
     *
     * @param  T  $permissionable
     * @param  'read'|'create'|'update'|'delete'|'share'  $permission
     * @return bool
     */
    public static function can(User $user, Model $permissionable, string $permission)
    {
        return $user->id == $permissionable?->user_id
            || $user->permissions()
                ->where('permissionable_type', get_class($permissionable))
                ->where('permissionable_id', $permissionable->id)
                ->where('permission_type', $permission)
                ->exists();
    }

    public static function setOwnerShip(User $user, Model $permittable)
    {
        foreach (Permission::PERMISSIONS as $permission) {
            Permission::create([
                'user_id' => $user->id,
                'permissionable_type' => $permittable::class,
                'permissionable_id' => $permittable->id,
                'permission_type' => $permission,
            ]);
        }
    }

    /**
     * @param  'read'|'create'|'update'|'delete'|'share'  $permission
     * @return Permission
     */
    public static function setPermission(User $user, Model $permittable, string $permission)
    {
        return Permission::create([
            'user_id' => $user->id,
            'permissionable_type' => get_class($permittable),
            'permissionable_id' => $permittable->id,
            'permission_type' => $permission,
        ]);
    }

    /**
     * @template T of Model
     *
     * @param  User  $user
     * @param  T::class  $className
     * @param  'read'|'create'|'update'|'delete'|'share'  $permission
     * @param  bool  $asQuery
     * @return \Illuminate\Support\Collection<int, T>|\Illuminate\Database\Eloquent\Builder<T>
     */
    public static function getShared($user, $className, $permission = Permission::READ, $asQuery = false)
    {
        $query = $className::whereHas('permissions', fn ($permission) => $permission->where('user_id', $user->id));

        return $asQuery ? $query : $query->get();
        // return $user->permissions()
        // ->where('permissionable_type', $className)
        // ->where('permission_type', $permission)
        // ->with(['permissionable'])->get()
        // ->pluck('permissionable');
    }

    /**
     * @template T of Model
     *
     * @param  User  $user
     * @param  T::class  $className
     * @param  'read'|'create'|'update'|'delete'|'share'  $permission
     * @param  bool  $asQuery
     * @return \Illuminate\Support\Collection<int, T>|\Illuminate\Database\Eloquent\Builder<T>
     */
    public static function getAllAccessible($user, $className, $permission = Permission::READ, $asQuery = false)
    {
        $query = $className::where('user_id', $user->id)
        ->orWhere('permissions.user_id', $user->id); // うまくいかなかったら↓に変える
        // ->orWhereHas('permissions', fn ($permission)=>$permission->where('user_id',$user->id))
        return $asQuery ? $query : $query->get();
    }
}
