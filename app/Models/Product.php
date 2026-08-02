<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'discount_starts_at',
        'discount_ends_at',
        'flash_sale_stock',
        'stock',
        'image',
        'is_active',
        'moderation_status',
        'moderation_note',
        'sold_count',
        'rating_avg',
        'rating_count',
    ];

    /**
     * Batas stok yang dianggap "menipis" untuk keperluan badge & notifikasi.
     */
    public const LOW_STOCK_THRESHOLD = 5;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:0',
            'discount_price' => 'decimal:0',
            'discount_starts_at' => 'datetime',
            'discount_ends_at' => 'datetime',
            'is_active' => 'boolean',
            'rating_avg' => 'decimal:2',
        ];
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function hasVariants(): bool
    {
        return $this->relationLoaded('variants')
            ? $this->variants->isNotEmpty()
            : $this->variants()->exists();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function questions()
    {
        return $this->hasMany(ProductQuestion::class)->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('moderation_status', 'approved');
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class)->latest();
    }

    public function stockAlerts()
    {
        return $this->hasMany(StockAlert::class);
    }

    public function isOnDiscount(): bool
    {
        if ($this->discount_price === null || (float) $this->discount_price >= (float) $this->price) {
            return false;
        }

        $now = now();

        if ($this->discount_starts_at && $now->lt($this->discount_starts_at)) {
            return false;
        }

        if ($this->discount_ends_at && $now->gt($this->discount_ends_at)) {
            return false;
        }

        if ($this->isFlashSaleSoldOut()) {
            return false;
        }

        return true;
    }

    /**
     * Diskon ini dipakai sebagai flash sale berkuota terbatas jika flash_sale_stock diisi.
     */
    public function hasFlashSaleQuota(): bool
    {
        return $this->flash_sale_stock !== null;
    }

    public function flashSaleRemaining(): ?int
    {
        if (! $this->hasFlashSaleQuota()) {
            return null;
        }

        return max(0, (int) $this->flash_sale_stock - (int) $this->flash_sale_sold);
    }

    public function isFlashSaleSoldOut(): bool
    {
        return $this->hasFlashSaleQuota() && $this->flashSaleRemaining() <= 0;
    }

    public function flashSaleProgressPercent(): int
    {
        if (! $this->hasFlashSaleQuota() || (int) $this->flash_sale_stock <= 0) {
            return 0;
        }

        return (int) min(100, round(((int) $this->flash_sale_sold / (int) $this->flash_sale_stock) * 100));
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->isOnDiscount() ? (float) $this->discount_price : (float) $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->isOnDiscount()) {
            return 0;
        }

        return (int) round((1 - ((float) $this->discount_price / (float) $this->price)) * 100);
    }

    /**
     * Stok efektif produk: jumlah stok seluruh varian jika produk punya varian,
     * atau stok produk itu sendiri jika tidak.
     */
    public function effectiveStock(): int
    {
        if ($this->hasVariants()) {
            return (int) ($this->relationLoaded('variants')
                ? $this->variants->sum('stock')
                : $this->variants()->sum('stock'));
        }

        return (int) $this->stock;
    }

    public function isLowStock(): bool
    {
        $stock = $this->effectiveStock();

        return $stock > 0 && $stock <= self::LOW_STOCK_THRESHOLD;
    }

    public function isOutOfStock(): bool
    {
        return $this->effectiveStock() <= 0;
    }

    public function stockBadgeLabel(): ?string
    {
        if ($this->isOutOfStock()) {
            return 'Stok Habis';
        }

        if ($this->isLowStock()) {
            return 'Stok Menipis';
        }

        return null;
    }

    /**
     * Ubah stok produk secara aman sambil mencatat riwayat perubahan stok.
     * $change positif menambah stok, negatif mengurangi stok.
     */
    public function applyStockChange(int $change, string $type, ?int $actorUserId = null, ?string $note = null): void
    {
        $wasLowOrOut = $this->isLowStock() || $this->isOutOfStock();
        $wasOut = $this->isOutOfStock();

        $this->stock = max(0, $this->stock + $change);
        $this->save();

        StockHistory::create([
            'product_id' => $this->id,
            'user_id' => $actorUserId,
            'type' => $type,
            'quantity_change' => $change,
            'stock_after' => $this->stock,
            'note' => $note,
        ]);

        // Beri notifikasi ke penjual hanya saat stok baru saja memasuki kondisi menipis/habis,
        // agar tidak mengirim notifikasi berulang setiap kali stok dikurangi sedikit demi sedikit.
        if (($this->isLowStock() || $this->isOutOfStock()) && ! $wasLowOrOut && $this->seller) {
            $this->seller->notify(new \App\Notifications\LowStockNotification($this));
        }

        // Beri tahu pembeli yang menunggu ("beri tahu saya") saat produk kembali tersedia.
        if ($wasOut && $this->stock > 0) {
            $waitingAlerts = $this->stockAlerts()->whereNull('notified_at')->with('user')->get();

            foreach ($waitingAlerts as $alert) {
                if ($alert->user) {
                    $alert->user->notify(new \App\Notifications\StockAvailableNotification($this));
                }
                $alert->update(['notified_at' => now()]);
            }
        }
    }

    /**
     * Recalculate and persist the cached rating average/count for this product.
     * Call this after a review is created, updated, or deleted.
     */
    public function refreshRatingCache(): void
    {
        $this->rating_avg = round((float) $this->reviews()->avg('rating'), 2);
        $this->rating_count = $this->reviews()->count();
        $this->saveQuietly();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/'.$this->image);
        }

        return 'https://placehold.co/600x600/e2e8f0/64748b?text='.urlencode($this->name);
    }
}
