<?php

namespace App\Models\Order;

use App\Models\Option\OptionValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProductOptionValue extends Model
{

    protected $fillable = [
        'order_product_id', 'order_product_option_id', 'option_value_id'
    ];

    public $timestamps = false;

    public function orderProduct(): BelongsTo
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function orderProductOption(): BelongsTo
    {
        return $this->belongsTo(OrderProductOption::class);
    }

    public function optionValueDetails(): BelongsTo
    {
        return $this->belongsTo(OptionValue::class, 'option_value_id');
    }

}
