<?php

namespace App\Models\SellerGroup;

use App\Enums\GeneralStatusEnum;
use App\Traits\TranslatedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerGroup extends Model
{
    use SoftDeletes, TranslatedAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'image',
        'automatic',
        'amount_sales',
        'order_count',
        'auto_assign',
        'status',
        'sort_order',
        'conditions_type'
    ];

    public $translatedAttributes = [
        'name',
        'description',
    ];


    public function translations(): HasMany
    {
        return $this->hasMany(SellerGroupTranslation::class);
    }

    public function conditions(): HasMany
    {
        return $this->hasMany(SellerGroupCondition::class);
    }

    public function seller_group_custom_product_prices(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SellerGroupCustomProductPrice::class);
    }

    public function seller_group_custom_prices(): HasMany
    {
        return $this->hasMany(SellerGroupCustomPrice::class);
    }


    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function child(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }


    public function getImageAttribute($value): string
    {
        if (isset($value))
            return asset('storage/uploads/sellerGroups' . '/' . $value);

        return url("images/no-image.png");
    }

    public function scopeActive($query)
    {
        return $query->where('status', GeneralStatusEnum::getStatusActive());
    }

}
