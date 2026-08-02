<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'address_id',
        'order_number',
        'total_amount',
        'discount_amount',
        'shipping_amount',
        'shipping_breakdown',
        'status',
        'shipping_address',
        'shipping_phone',
        'shipping_city',
        'payment_method',
        'coupon_code',
        'courier',
        'tracking_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:0',
            'discount_amount' => 'decimal:0',
            'shipping_amount' => 'decimal:0',
            'shipping_breakdown' => 'array',
        ];
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'processing' => 'blue',
            'shipped' => 'indigo',
            'completed' => 'emerald',
            'cancelled' => 'rose',
            default => 'slate',
        };
    }

    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            'transfer_bank' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet (OVO / GoPay / Dana)',
            'cod' => 'Bayar di Tempat (COD)',
            default => str_replace('_', ' ', ucfirst($this->payment_method)),
        };
    }

    public function courierLabel(): ?string
    {
        return match ($this->courier) {
            'jne_reg' => 'JNE Reguler',
            'jnt_express' => 'J&T Express',
            'sicepat' => 'SiCepat',
            'grab_instant' => 'GrabExpress Instant',
            null => null,
            default => $this->courier,
        };
    }
}
