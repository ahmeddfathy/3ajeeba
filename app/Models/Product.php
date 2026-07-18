<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'details',
        'image',
        'price',
        'original_price',
        'discount_type',
        'discount_value',
        'ribbon_label',
        'is_featured',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'integer',
        'original_price' => 'integer',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->active();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        $price = $this->display_price;
        $original = $this->display_original_price;

        if (! $original || ! $price || $original <= $price) {
            return null;
        }

        if ($this->discount_type === 'percentage' && $this->discount_value) {
            return (int) $this->discount_value;
        }

        return (int) round((1 - $price / $original) * 100);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset($this->image);
    }

    public function getHasVariantsAttribute(): bool
    {
        if ($this->relationLoaded('activeVariants')) {
            return $this->activeVariants->isNotEmpty();
        }

        if ($this->relationLoaded('variants')) {
            return $this->variants->where('is_active', true)->isNotEmpty();
        }

        return $this->activeVariants()->exists();
    }

    /** أقل سعر معروض (من الفاريانتس أو سعر المنتج) */
    public function getDisplayPriceAttribute(): int
    {
        $variants = $this->relationLoaded('activeVariants')
            ? $this->activeVariants
            : ($this->relationLoaded('variants')
                ? $this->variants->where('is_active', true)
                : $this->activeVariants()->get());

        if ($variants->isNotEmpty()) {
            return (int) $variants->min('price');
        }

        return (int) ($this->price ?? 0);
    }

    public function getDisplayOriginalPriceAttribute(): ?int
    {
        $variants = $this->relationLoaded('activeVariants')
            ? $this->activeVariants
            : ($this->relationLoaded('variants')
                ? $this->variants->where('is_active', true)
                : $this->activeVariants()->get());

        if ($variants->isNotEmpty()) {
            $cheapest = $variants->sortBy('price')->first();

            return $cheapest->original_price && $cheapest->original_price > $cheapest->price
                ? (int) $cheapest->original_price
                : null;
        }

        return $this->original_price && $this->original_price > $this->price
            ? (int) $this->original_price
            : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
