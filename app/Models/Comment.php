<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'body',
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
