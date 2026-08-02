<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'store_name',
        'store_slug',
        'store_description',
        'avatar',
        'store_banner',
        'store_status',
        'store_status_note',
        'is_suspended',
        'shipping_type',
        'shipping_flat_rate',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_suspended' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * Dianggap online jika ada aktivitas dalam 5 menit terakhir.
     */
    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStoreApproved(): bool
    {
        return $this->store_status === 'approved';
    }

    public function storeStatusLabel(): string
    {
        return match ($this->store_status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Aktif',
            'suspended' => 'Dinonaktifkan',
            default => ucfirst((string) $this->store_status),
        };
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    /**
     * Rata-rata rating toko, dihitung dari rata-rata rating seluruh produk yang sudah punya ulasan.
     */
    public function storeRatingAvg(): float
    {
        return round((float) $this->products()->where('rating_count', '>', 0)->avg('rating_avg'), 2);
    }

    /**
     * Total jumlah ulasan dari seluruh produk toko ini.
     */
    public function storeRatingCount(): int
    {
        return (int) $this->products()->sum('rating_count');
    }

    /**
     * Badge toko yang ditampilkan di halaman toko & produk, dihitung otomatis dari
     * rating, jumlah penjualan, dan usia toko. Bisa mengembalikan lebih dari satu badge.
     */
    public function storeBadges(): array
    {
        $badges = [];

        if (! $this->isSeller()) {
            return $badges;
        }

        $ratingAvg = $this->storeRatingAvg();
        $ratingCount = $this->storeRatingCount();
        $totalSold = (int) $this->products()->sum('sold_count');

        if ($ratingAvg >= 4.5 && $ratingCount >= 10) {
            $badges[] = ['label' => 'Toko Pilihan', 'icon' => '⭐', 'color' => 'amber'];
        } elseif ($ratingAvg >= 4.0 && $totalSold >= 50) {
            $badges[] = ['label' => 'Toko Terpercaya', 'icon' => '🛡️', 'color' => 'forest'];
        }

        if ($totalSold >= 200) {
            $badges[] = ['label' => 'Top Seller', 'icon' => '🔥', 'color' => 'clay'];
        }

        if ($this->created_at && $this->created_at->gt(now()->subDays(30))) {
            $badges[] = ['label' => 'Toko Baru', 'icon' => '✨', 'color' => 'ink'];
        }

        return $badges;
    }

    /**
     * ID semua penjual yang memenuhi kriteria "Toko Terpercaya/Toko Pilihan"
     * (rating tinggi dari ulasan asli + jumlah penjualan/ulasan yang cukup banyak).
     * Dihitung lewat satu query agregat supaya efisien dipakai untuk filter katalog,
     * bukan dengan memanggil storeBadges() per produk (yang akan N+1 query).
     */
    public static function trustedSellerIds(): array
    {
        return DB::table('products')
            ->selectRaw('seller_id,
                AVG(CASE WHEN rating_count > 0 THEN rating_avg END) AS avg_rating,
                SUM(CASE WHEN rating_count > 0 THEN rating_count ELSE 0 END) AS total_reviews,
                SUM(sold_count) AS total_sold')
            ->groupBy('seller_id')
            ->havingRaw('(avg_rating >= 4.5 AND total_reviews >= 10) OR (avg_rating >= 4.0 AND total_sold >= 50)')
            ->pluck('seller_id')
            ->all();
    }

    public function stockAlerts()
    {
        return $this->hasMany(StockAlert::class);
    }

    public function quickReplies()
    {
        return $this->hasMany(QuickReply::class, 'seller_id');
    }

    public function hasStockAlertFor(Product $product): bool
    {
        return $this->stockAlerts()->where('product_id', $product->id)->whereNull('notified_at')->exists();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'seller_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function shippingCityRates()
    {
        return $this->hasMany(ShippingCityRate::class, 'seller_id');
    }

    /**
     * Hitung ongkir toko ini untuk kota tujuan tertentu.
     * Jika tipe ongkir toko adalah per_city dan kota tidak terdaftar, dianggap tidak melayani
     * kota tersebut sehingga menggunakan tarif flat sebagai fallback.
     */
    public function shippingRateForCity(?string $city): int
    {
        if ($this->shipping_type === 'per_city' && $city) {
            $rate = $this->shippingCityRates()->where('city', $city)->value('rate');

            if ($rate !== null) {
                return (int) $rate;
            }
        }

        return (int) $this->shipping_flat_rate;
    }

    /**
     * Toko-toko yang diikuti oleh user ini (sebagai buyer).
     */
    public function following()
    {
        return $this->hasMany(StoreFollow::class, 'user_id');
    }

    /**
     * Follower dari toko user ini (sebagai seller).
     */
    public function followers()
    {
        return $this->hasMany(StoreFollow::class, 'seller_id');
    }

    public function isFollowing(User $seller): bool
    {
        return $this->following()->where('seller_id', $seller->id)->exists();
    }

    /**
     * Percakapan chat, baik sebagai buyer maupun seller.
     */
    public function conversationsAsBuyer()
    {
        return $this->hasMany(Conversation::class, 'buyer_id');
    }

    public function conversationsAsSeller()
    {
        return $this->hasMany(Conversation::class, 'seller_id');
    }

    public function unreadMessagesCount(): int
    {
        return Message::whereIn('conversation_id', function ($query) {
            $query->select('id')->from('conversations')
                ->where('buyer_id', $this->id)
                ->orWhere('seller_id', $this->id);
        })
            ->where('sender_id', '!=', $this->id)
            ->whereNull('read_at')
            ->count();
    }
}
