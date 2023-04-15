<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Task;
use App\Models\Record;
use App\Models\CalendarEvent;

use Illuminate\Database\Eloquent\Casts\Attribute;
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $role
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $icon_url
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CalendarEvent> $calendarEvents
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Record> $records
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Task> $tasks
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'icon_url'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = [
        'url'
    ];

    public function can($permission, $permittable)
    {
        return PermissionService::can($this, $permittable, $permission);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);

    }
    public function records()
    {
        return $this->hasMany(Record::class);
    }
    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('task.show', $this->id),
        );
    }
    // public function permissions()
    // {
    //     return $this->hasMany(Permission::class);
    // }
    // public function allTasks()
    // {
    //     return $this->morphMany(Task::class, 'target');
    // }
    // public function allCalendarEvents()
    // {
    //     return $this->morphMany(CalendarEvent::class, 'target');
    // }
    // public function allRecords()
    // {
    //     return $this->morphMany(Record::class, 'target');
    // }
}
