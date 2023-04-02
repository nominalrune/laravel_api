<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'created_at',
        'updated_at',
    ];
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
