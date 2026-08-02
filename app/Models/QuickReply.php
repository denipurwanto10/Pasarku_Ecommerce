<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickReply extends Model
{
    protected $fillable = [
        'seller_id',
        'title',
        'body',
        'sort_order',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
