<?php

namespace App\Models;

use App\Models\Seller\Seller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'recharge_balance_type',
        'bank_name',
        'transferring_name',
        'receipt_image',
        'notes',
        'amount',
        'type',
        'seller_id'
    ];
    public function seller() : BelongsTo
    {
        return $this->belongsTo(Seller::class,'seller_id');
    }
    public function getReceiptImageUrlAttribute($value): string
    {
        if (isset($value))
            return asset('storage/uploads/receipts' . '/' . $value);

        return url("images/no-image.png");
    }
}
