<?php

namespace App\Models\SellerGroup;

use App\Traits\TranslatesName;
use Illuminate\Database\Eloquent\Model;

class SellerGroupCustomProductPrice extends Model
{

    use TranslatesName;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'advantage',
        'type',
        'amount',
        'seller_group_id',
    ];

    public function seller_group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SellerGroup::class);
    }


}
