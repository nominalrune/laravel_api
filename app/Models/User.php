<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
 * @mixin IdeHelperUser
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
 * @property-read int|null $calendar_events_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Record> $records
 * @property-read int|null $records_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIconUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
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

    public function tasks()
    {
        return $this->hasMany(Task::class, 'owner_id');
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
