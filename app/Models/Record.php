<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Casts\Attribute;
class Record extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'description',
        'user_id',
        'topic_type',
        'topic_id',
        'date',
        'time',
    ];
    protected $appends = [
        'url'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function topic()
    {
        return $this->morphTo();
    }
    public function permissions()
    {
        return $this->morphMany(Permission::class, 'target', 'target_type', 'target_id');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
        protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('record.show', $this->id),
        );
    }
}
