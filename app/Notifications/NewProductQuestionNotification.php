<?php

namespace App\Notifications;

use App\Models\ProductQuestion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewProductQuestionNotification extends Notification
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
            'title' => 'Ada pertanyaan baru',
            'message' => $this->question->user->name.' bertanya tentang "'.$product->name.'".',
            'product_id' => $product->id,
            'question_id' => $this->question->id,
            'url' => route('products.show', $product).'#tanya-jawab',
        ];
    }
}
