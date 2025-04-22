<?php

namespace App\Models\SellerGroup;

use App\Traits\TranslatesName;
use Illuminate\Database\Eloquent\Model;

class SellerGroupCustomPrice extends Model
{

    use TranslatesName;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model_id',
        'seller_group_id',
        'advantage',
        'type',
        'amount',
    ];

    public function seller_group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SellerGroup::class);
    }


}
