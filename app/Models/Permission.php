<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
;
use App\Models\Task;
use App\Models\User;



/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $target_type
 * @property int $target_id
 * @property int $user_id
 * @property string $permission_type
 * @property-read Model|\Eloquent $target
 * @property-read User $user
 */
class Permission extends Model
{
    use HasFactory;
    public const READ = 'read';
    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const DELETE = 'delete';
    public const SHARE = 'share';
    public const PERMISSIONS = [
        self::READ,
        self::CREATE,
        self::UPDATE,
        self::DELETE,
        self::SHARE,
    ];


    public $timestamps = false;
    protected $fillable = [
        'permissionable_type',
        'permissionable_id',
        'permission_type',
        'user_id',
    ];

    public function permissionable()
    {
        return $this->morphTo('target', 'target_type', 'target_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
