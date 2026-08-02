<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'product_id', 'product_variant_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Harga satuan efektif: harga varian jika item ini punya varian, jika tidak harga produk.
     */
    public function getUnitPriceAttribute(): float
    {
        return $this->variant ? $this->variant->final_price : $this->product->final_price;
    }

    /**
     * Stok yang tersedia untuk item ini: stok varian jika ada, jika tidak stok produk.
     */
    public function getAvailableStockAttribute(): int
    {
        return $this->variant ? $this->variant->stock : $this->product->stock;
    }

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }
}
