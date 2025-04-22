<?php

namespace App\Models;


use App\Models\Brand\Brand;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorProduct extends Model
{

    use HasFactory;


    protected $fillable = [
        'vendor_id',
        'product_id',
        'brand_id',
        'type',
        'vendor_product_id',
        'provider_cost'
    ];


    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

}
