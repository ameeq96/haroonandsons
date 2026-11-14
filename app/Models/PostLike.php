<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostLike extends Model
{
    protected $fillable = [
        'post_id',
        'visitor_token',
        'ip_hash',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
