<?php

namespace App\Models\Cart;

use App\Models\CartProductOption;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CartProduct extends Model
{

    protected $fillable = [
        'cart_id',
        'product_id',
        'category_id',
        'quantity',
        'is_max_quantity_one',
        'type',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(CartProductOption::class);
    }

}
