<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockAvailableNotification extends Notification
{
    use Queueable;

    public function __construct(public Product $product)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Stok tersedia lagi',
            'message' => '"'.$this->product->name.'" yang kamu tunggu kini tersedia kembali. Yuk beli sebelum kehabisan lagi!',
            'product_id' => $this->product->id,
            'url' => route('products.show', $this->product),
        ];
    }
}
