<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
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
        $isOut = $this->product->stock <= 0;

        return [
            'title' => $isOut ? 'Stok produk habis' : 'Stok produk menipis',
            'message' => '"'.$this->product->name.'" '.($isOut ? 'sudah habis' : 'tersisa '.$this->product->stock.' unit').'. Segera lakukan restock.',
            'product_id' => $this->product->id,
            'stock' => $this->product->stock,
            'url' => route('seller.products.edit', $this->product),
        ];
    }
}
