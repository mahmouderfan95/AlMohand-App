<?php

namespace App\Models\Order;


use App\Models\Brand\Brand;
use App\Models\Product\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderProduct extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'brand_id',
        'vendor_id',
        'type',
        'status',
        'total',
        'quantity',
        'cost_price',
        'wholesale_price',
        'price',
        'profit',
        'tax_value',
    ];

    use HasFactory;

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderProductSerials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderProductSerial::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(OrderProductOption::class);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
