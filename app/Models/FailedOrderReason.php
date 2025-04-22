<?php

namespace App\Models;


use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FailedOrderReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_product_id',
        'reason',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }


}
