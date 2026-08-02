<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PriceDropNotification extends Notification
{
    use Queueable;

    public function __construct(public Product $product, public float $oldPrice)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $newPrice = (float) $this->product->final_price;
        $percentOff = $this->oldPrice > 0
            ? (int) round((1 - ($newPrice / $this->oldPrice)) * 100)
            : 0;

        return [
            'title' => 'Harga turun di wishlist kamu',
            'message' => '"'.$this->product->name.'" turun harga jadi Rp'.number_format($newPrice, 0, ',', '.')
                .($percentOff > 0 ? " (hemat {$percentOff}%)" : '').'. Buruan cek sebelum naik lagi!',
            'product_id' => $this->product->id,
            'url' => route('products.show', $this->product),
        ];
    }
}
