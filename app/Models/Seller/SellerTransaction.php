<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Model;

class SellerTransaction extends Model
{


    protected $fillable = [
        'seller_id',
        'amount',
        'note',
        'balance',
        'type',
    ];


    public function seller(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

}
