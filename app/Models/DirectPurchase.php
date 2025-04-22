<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DirectPurchase extends Model
{

    protected $fillable = [
        'product_id', 'status'
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function directPurchasePriorities(): HasMany
    {
        return $this->hasMany(DirectPurchasePriority::class);
    }



}
