<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string $commentable_type
 * @property int $commentable_id
 * @property string $content
 * @property-read Model|\Eloquent $commentable
 */
class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'user_id',
        'commentable_id',
        'commentable_type',
    ];
    protected $appends = [
        'url'
    ];
    public function commentable()
    {
        return $this->morphTo();
    }
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->commentable->url.'#comment-'.$this->id,
        );
    }
}
