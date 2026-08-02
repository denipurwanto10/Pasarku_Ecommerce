<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price_adjustment',
        'stock',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_adjustment' => 'integer',
            'stock' => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Harga akhir varian ini: harga efektif produk (setelah diskon jika ada) ditambah selisih varian.
     */
    public function getFinalPriceAttribute(): float
    {
        $base = $this->product ? (float) $this->product->final_price : 0;

        return max(0, $base + (float) $this->price_adjustment);
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }
}
