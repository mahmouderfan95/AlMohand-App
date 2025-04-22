<?php

namespace App\Models\SellerGroup;

use App\Models\Language\Language;
use Illuminate\Database\Eloquent\Model;

class SellerGroupTranslation extends Model
{
    protected $fillable = [
        'seller_group_id',
        'language_id',
        'name',
        'description',
    ];
    public $timestamps = false;

    public function language(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
    public function sellerGroup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SellerGroup::class);
    }
}
