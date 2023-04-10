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
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission wherePermissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUserId($value)
 * @mixin \Eloquent
 */
class Permission extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'target_type',
        'target_id',
        'permission_type',
        'user_id',
    ];

    public function target()
    {
        return $this->morphTo('target', 'target_type', 'target_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
