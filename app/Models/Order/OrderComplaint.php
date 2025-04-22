<?php

namespace App\Models\Order;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderComplaint extends Model
{

    protected $fillable = [
        'order_id', 'customer_id', 'description', 'status'
    ];


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


}
