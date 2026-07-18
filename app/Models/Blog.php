<?php

namespace App\Models;

use App\Support\ArabicSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_alt',
        'meta_title',
        'meta_description',
        'tags',
        'is_published',
        'published_at',
        'blog_category_id',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (Blog $blog) {
            if (! $blog->slug) {
                $blog->slug = ArabicSlug::make($blog->title);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        return str_starts_with($this->featured_image, 'http')
            ? $this->featured_image
            : asset($this->featured_image);
    }

    public function getExcerptTextAttribute(): string
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }

        return str($this->content ? strip_tags($this->content) : '')->limit(140)->toString();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
