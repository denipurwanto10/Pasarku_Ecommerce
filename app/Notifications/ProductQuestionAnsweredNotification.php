<?php

namespace App\Notifications;

use App\Models\ProductQuestion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductQuestionAnsweredNotification extends Notification
{
    use Queueable;

    public function __construct(public ProductQuestion $question)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $product = $this->question->product;

        return [
            'title' => 'Pertanyaanmu dijawab',
            'message' => 'Penjual "'.($product->seller->store_name ?? $product->seller->name).'" menjawab pertanyaanmu tentang "'.$product->name.'".',
            'product_id' => $product->id,
            'question_id' => $this->question->id,
            'url' => route('products.show', $product).'#tanya-jawab',
        ];
    }
}
