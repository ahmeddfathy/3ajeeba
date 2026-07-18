<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'governorate',
        'address',
        'notes',
        'total_amount',
        'status',
        'source',
        'created_by',
        'admin_notes',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'shipped_at'   => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(OrderLog::class)->latest();
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isWebOrder(): bool
    {
        return $this->source === 'website';
    }

    public function canBeDeletedBy(?User $user): bool
    {
        if (!$user) return false;
        if ($this->isWebOrder()) return $user->role === 'admin';
        return $user->role === 'admin' || $this->created_by === $user->id;
    }

    public function canBeEditedBy(?User $user): bool
    {
        if (!$user) return false;
        // both admin and moderator can edit
        return in_array($user->role, ['admin', 'moderator']) || $this->created_by === $user->id;
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public static function generateOrderNumber(): string
    {
        $lastOrder = self::latest('id')->first();
        $nextNum   = $lastOrder ? ($lastOrder->id + 1) : 1;
        return 'MC-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->getStatusLabelFor($this->status);
    }

    public function getStatusLabelFor(string $status): string
    {
        return match ($status) {
            'new'       => 'جديد',
            'confirmed' => 'مؤكد',
            'preparing' => 'قيد التجهيز',
            'shipped'   => 'تم الشحن',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
            'returned'  => 'مرتجع',
            default     => $status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'new'       => 'blue',
            'confirmed' => 'purple',
            'preparing' => 'orange',
            'shipped'   => 'indigo',
            'delivered' => 'green',
            'cancelled' => 'red',
            'returned'  => 'gray',
            default     => 'gray',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'new'       => 'bi-bell-fill',
            'confirmed' => 'bi-check-circle-fill',
            'preparing' => 'bi-box-seam-fill',
            'shipped'   => 'bi-truck',
            'delivered' => 'bi-house-check-fill',
            'cancelled' => 'bi-x-circle-fill',
            'returned'  => 'bi-arrow-return-left',
            default     => 'bi-question-circle',
        };
    }

    public function getNextStatusAttribute(): ?string
    {
        return match ($this->status) {
            'new'       => 'confirmed',
            'confirmed' => 'preparing',
            'preparing' => 'shipped',
            'shipped'   => 'delivered',
            default     => null,
        };
    }

    public function getNextStatusLabelAttribute(): ?string
    {
        if (!$this->next_status) return null;

        return match ($this->next_status) {
            'confirmed' => 'تأكيد الطلب',
            'preparing' => 'بدء التجهيز',
            'shipped'   => 'شحن الطلب',
            'delivered' => 'تسليم الطلب',
            default     => null,
        };
    }
}
