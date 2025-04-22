<?php

namespace App\Models\Product;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSerial extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'product_id',
        'serial',
        'scratching',
        'status',
        'buying',
        'expiring',
        'price_before_vat',
        'vat_amount',
        'price_after_vat',
        'currency',
    ];


    public function invoice(): BelongsTo
    {
        return $this->BelongsTo(Invoice::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function getFileAttribute($value): string
    {
        if (isset($value))
            return asset('storage/uploads/products' . '/' . $value);

        return url("images/no-image.png");
    }


}
