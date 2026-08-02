<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification
{
    use Queueable;

    public function __construct(public Review $review)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $product = $this->review->product;

        return [
            'title' => 'Ulasan baru masuk',
            'message' => $this->review->user->name.' memberi rating '.$this->review->rating.'/5 untuk "'.$product->name.'".',
            'product_id' => $product->id,
            'review_id' => $this->review->id,
            'rating' => $this->review->rating,
            'url' => route('products.show', $product),
        ];
    }
}
