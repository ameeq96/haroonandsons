<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostReview extends Model
{
    protected $fillable = [
        'post_id',
        'reviewer_name',
        'reviewer_email',
        'rating',
        'review_text',
        'status',
    ];

    protected $hidden = ['reviewer_email'];

    protected function casts(): array
    {
        return ['rating' => 'integer'];
    }

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
