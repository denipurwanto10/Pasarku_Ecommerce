<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCityRate extends Model
{
    protected $fillable = [
        'seller_id',
        'city',
        'rate',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
