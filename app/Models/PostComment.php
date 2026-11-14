<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostComment extends Model
{
    protected $fillable = [
        'post_id',
        'commenter_name',
        'commenter_email',
        'comment_text',
        'status',
    ];

    protected $hidden = ['commenter_email'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
