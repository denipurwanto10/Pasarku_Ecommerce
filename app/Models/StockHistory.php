<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity_change',
        'stock_after',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'order' => 'Terjual (Pesanan)',
            'restock' => 'Penambahan Stok',
            'adjustment' => 'Penyesuaian Manual',
            'return' => 'Pengembalian',
            default => ucfirst($this->type),
        };
    }
}
