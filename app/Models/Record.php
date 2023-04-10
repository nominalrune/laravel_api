<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\Record
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string|null $description
 * @property string|null $topic_type
 * @property int|null $topic_id
 * @property string|null $date
 * @property int|null $time
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\RecordFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Record newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Record newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Record query()
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereTopicType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereUserId($value)
 * @mixin \Eloquent
 */
class Record extends Model
{
    use HasFactory;
    protected $fillable = [
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
    // public function topic()
    // {
    //     return $this->morphTo();
    // }
    // public function permissions()
    // {
    //     return $this->morphMany(Permission::class, 'target', 'target_type', 'target_id');
    // }
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
