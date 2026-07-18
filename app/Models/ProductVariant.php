<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'color_hex',
        'price',
        'original_price',
        'sku',
        'stock',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'original_price' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getLabelAttribute(): string
    {
        $parts = array_filter([$this->color, $this->size]);

        return $parts ? implode(' / ', $parts) : 'افتراضي';
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (! $this->original_price || $this->original_price <= $this->price) {
            return null;
        }

        return (int) round((1 - $this->price / $this->original_price) * 100);
    }
}
