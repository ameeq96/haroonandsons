<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = [
        'title',
        'title_zh',
        'slug',
        'featured_image',
        'gallery_images',
        'excerpt',
        'excerpt_zh',
        'content',
        'content_zh',
        'category',
        'category_zh',
        'status',
        'published_at',
        'seo_title',
        'seo_title_zh',
        'meta_description',
        'meta_description_zh',
        'video_path',
        'video_mime_type',
        'video_size',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'gallery_images' => 'array'];
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', Carbon::now());
    }

    // ─── Bilingual Accessors ──────────────────────────────────────────────────

    public function translatedTitle(): string
    {
        $locale = App::getLocale();
        if ($locale === 'zh' && !empty($this->title_zh)) {
            return $this->title_zh;
        }
        return $this->title ?? '';
    }

    public function translatedExcerpt(): ?string
    {
        $locale = App::getLocale();
        if ($locale === 'zh' && !empty($this->excerpt_zh)) {
            return $this->excerpt_zh;
        }
        return $this->excerpt;
    }

    public function translatedContent(): string
    {
        $locale = App::getLocale();
        if ($locale === 'zh' && !empty($this->content_zh)) {
            return $this->content_zh;
        }
        return $this->content ?? '';
    }

    public function translatedCategory(): ?string
    {
        $locale = App::getLocale();
        if ($locale === 'zh' && !empty($this->category_zh)) {
            return $this->category_zh;
        }
        return $this->category;
    }

    public function translatedSeoTitle(): ?string
    {
        $locale = App::getLocale();
        if ($locale === 'zh' && !empty($this->seo_title_zh)) {
            return $this->seo_title_zh;
        }
        return $this->seo_title;
    }

    public function translatedMetaDescription(): ?string
    {
        $locale = App::getLocale();
        if ($locale === 'zh' && !empty($this->meta_description_zh)) {
            return $this->meta_description_zh;
        }
        return $this->meta_description;
    }

    // ─── Image Accessors ─────────────────────────────────────────────────────

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image
            ? Storage::disk('public')->url($this->featured_image)
            : null;
    }

    public function getImagesAttribute(): array
    {
        return array_values(array_unique(array_merge(
            $this->featured_image ? [$this->featured_image] : [],
            $this->gallery_images ?? []
        )));
    }

    public function getImageUrlsAttribute(): array
    {
        return array_map(fn (string $image): string => Storage::disk('public')->url($image), $this->images);
    }

    public function getVideoUrlAttribute(): ?string
    {
        return $this->video_path ? Storage::disk('public')->url($this->video_path) : null;
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function reviews(): HasMany
    {
        return $this->hasMany(PostReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(PostReview::class)->where('status', 'approved')->latest();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(PostComment::class)->where('status', 'approved')->latest();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    // ─── Computed Stats ───────────────────────────────────────────────────────

    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    public function getViewsCountAttribute(): int
    {
        return $this->views()->count();
    }

    public function getApprovedReviewsCountAttribute(): int
    {
        return $this->reviews()->where('status', 'approved')->count();
    }

    public function getAverageRatingAttribute(): ?float
    {
        $avg = $this->reviews()->where('status', 'approved')->avg('rating');
        return $avg ? round($avg, 1) : null;
    }
}
