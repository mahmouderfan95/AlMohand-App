<?php

namespace App\Models\Order;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPaymentTransaction extends Model
{

    use HasFactory;


    protected $fillable = [
        'order_id',
        'full_response',
        'reference_number',
        'payment_id',
        'payment_type',
        'amount',
        'currency',
        'paymentBrand',
        'last_4_digits',
    ];


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }



}
