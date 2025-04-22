<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderGift extends Model
{

    protected $fillable = [
        'order_id', 'recipient_name', 'recipient_email', 'image', 'description'
    ];

    public $timestamps = false;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }


    public function getImageAttribute($value)
    {
        if (isset($value))
            return  asset('/storage/uploads/orders'). '/'.$value;
        return  asset('/images/no-image.png');
    }

}
