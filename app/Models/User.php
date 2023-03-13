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

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
    public function allTasks()
    {
        return $this->morphMany(Task::class, 'target');
    }
    public function allCalendarEvents()
    {
        return $this->morphMany(CalendarEvent::class, 'target');
    }
    public function allRecords()
    {
        return $this->morphMany(Record::class, 'target');
    }
}
