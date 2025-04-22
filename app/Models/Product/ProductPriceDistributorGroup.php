<?php

namespace App\Models\Product;

use App\Models\Distributor\DistributorGroup;
use App\Models\SellerGroup\SellerGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPriceDistributorGroup extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'distributor_group_id',
        'price',
        'amount_percentage',
        'minimum_quantity',
        'max_quantity',
        'points_buy',
        'points_sell',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function distributorGroup(): BelongsTo
    {
        return $this->belongsTo(DistributorGroup::class);
    }



}
