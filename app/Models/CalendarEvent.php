<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;
/**
 * App\Models\CalendarEvent
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string|null $description
 * @property string $start_at
 * @property string|null $end_at
 * @property int $user_id
 * @property-read \App\Models\User|null $user
 */
class CalendarEvent extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'description',
        'start_at',
        'end_at',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
