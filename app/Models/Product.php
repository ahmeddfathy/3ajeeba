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
        $path = $this->resolvePublicImagePath($this->image);

        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset($path);
    }

    /**
     * Resolve a public image path, with fallbacks for seed images
     * that live under gitignored upload folders on some deploys.
     */
    public function resolvePublicImagePath(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (is_file(public_path($normalized))) {
            return $normalized;
        }

        $fallbacks = [
            'assets/images/products/abaya-1.jpg' => 'assets/images/store/p1.jpg',
            'assets/images/products/abaya-2.jpg' => 'assets/images/store/p2.jpg',
            'assets/images/products/abaya-3.jpg' => 'assets/images/store/p3.jpg',
            'assets/images/products/abaya-4.jpg' => 'assets/images/store/p4.jpg',
        ];

        if (isset($fallbacks[$normalized]) && is_file(public_path($fallbacks[$normalized]))) {
            return $fallbacks[$normalized];
        }

        if (preg_match('/abaya-([1-4])\.jpe?g$/i', $normalized, $matches)) {
            $alt = 'assets/images/store/p' . $matches[1] . '.jpg';
            if (is_file(public_path($alt))) {
                return $alt;
            }
        }

        return null;
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
