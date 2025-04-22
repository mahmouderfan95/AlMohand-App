<?php

namespace App\Models;

use App\Enums\ProductSerialType;
use App\Models\Product\Product;
use App\Models\Product\ProductSerial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id',
        'product_id',
        'user_id',
        'invoice_number',
        'type',
        'status',
        'price',
        'quantity',
    ];


    public function patches(): HasMany
    {
        return $this->hasMany(Patch::class, 'invoice_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function productSerials(): HasMany
    {
        return $this->hasMany(ProductSerial::class);
    }


    public function getStatusAttribute()
    {
        $invoiceProductSerialsFreeCount = $this->productSerials()
            ->where('status', ProductSerialType::getTypeFree())
            ->count();
        $invoiceProductSerialsHoldCount = $this->productSerials()
            ->where('status', ProductSerialType::getTypeHold())
            ->count();
        $invoiceProductSerialsStoppedCount = $this->productSerials()
            ->where('status', ProductSerialType::getTypeStopped())
            ->count();
        if ($invoiceProductSerialsFreeCount > 0) {
            return ProductSerialType::getTypeFree();
        }elseif ($invoiceProductSerialsHoldCount > 0) {
            return ProductSerialType::getTypeHold();
        }elseif ($invoiceProductSerialsStoppedCount > 0) {
            return ProductSerialType::getTypeStopped();
        }else{
            return ProductSerialType::getTypeSold();

        }
    }


}
