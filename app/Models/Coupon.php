<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'seller_id',
        'code',
        'type',
        'value',
        'max_discount',
        'min_purchase',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Check whether the coupon can currently be used, regardless of cart contents.
     */
    public function isRedeemable(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Calculate the discount amount for a given eligible subtotal (the subtotal
     * of items in the cart that belong to this coupon's seller).
     */
    public function calculateDiscount(int $eligibleSubtotal): int
    {
        if ($this->type === 'percent') {
            $discount = (int) floor($eligibleSubtotal * $this->value / 100);

            if ($this->max_discount !== null) {
                $discount = min($discount, (int) $this->max_discount);
            }
        } else {
            $discount = (int) $this->value;
        }

        return min($discount, $eligibleSubtotal);
    }

    public function typeLabel(): string
    {
        return $this->type === 'percent' ? 'Persentase' : 'Nominal Tetap';
    }
}
