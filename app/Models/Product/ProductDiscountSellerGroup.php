<?php

namespace App\Models\Product;

use App\Models\SellerGroup\SellerGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDiscountSellerGroup extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'seller_group_id',
        'price',
        'amount_percentage',
        'minimum_quantity',
        'max_quantity',
        'from',
        'to',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function seller_group(): BelongsTo
    {
        return $this->belongsTo(SellerGroup::class);
    }



}
