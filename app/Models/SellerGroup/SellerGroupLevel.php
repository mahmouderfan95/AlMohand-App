<?php

namespace App\Models\SellerGroup;

use App\Traits\TranslatedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerGroupLevel extends Model
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
        'status',
        'sort_order'
    ];


    public $translatedAttributes = [
        'name',
        'desc'
    ];


    public function translations(): HasMany
    {
        return $this->hasMany(SellerGroupLevelTranslation::class);
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
        return $query->where('status', "active");
    }

}
