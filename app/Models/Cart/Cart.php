<?php

namespace App\Models\Cart;

use App\Exceptions\Cart\cartBadUse;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Cart extends Model
{

    protected $fillable = ['owner_type', 'owner_id','cart_price', 'tax_rate','total_price'];

    protected $appends = ['cart_price', 'tax_rate','total_price'];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
    public function cartProducts(): HasMany
    {
        return $this->hasMany(CartProduct::class);
    }
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_products');
    }

    public function getCartPriceAttribute()
    {
        // Initialize cart price
        $cartPrice = 0;
        // Iterate through products in the cart
        foreach ($this->cartProducts as $cartProduct) {
            // Add the price of each product multiplied by its quantity to the cart price
            $cartPrice += $cartProduct->product->price * $cartProduct->quantity;
        }
        return round($cartPrice, 3);
    }
    public function getTaxRateAttribute()
    {
        return 0.0;
    }
    public function getTotalPriceAttribute()
    {
        return $this->cart_price + $this->tax_rate;
    }


}
