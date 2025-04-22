<?php

namespace App\Models\Category;

use App\Models\Brand\Brand;
use App\Models\Product\Product;
use App\Traits\ApiResponseAble;
use App\Traits\TranslatedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, TranslatedAttributes, ApiResponseAble;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'parent_id',
        'brand_id',
        'level',
        'status',
        'web',
        'mobile',
        'sort_order'
    ];

    public $translatedAttributes = [
        'name',
        'description',
        // 'meta_title',
        // 'meta_keyword',
        // 'meta_description'
    ];

    protected $hidden = ['pivot'];


    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function category_brands(): HasMany
    {
        return $this->hasMany(CategoryBrand::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'category_brands');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function child(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function ancestors()
    {
        return $this->parent()->with('ancestors');
    }


    public function getImageAttribute($value): string
    {
        if (isset($value) && $value != 'images/no-image.png'){
            return asset('/storage/uploads/categories'). '/'.$value;
        }
        return asset('/storage/uploads/categories'). '/product-card-placeholder.png';
    }

    public function scopeActive($query)
    {
        return $query->where('categories.status', "active");
    }

    public function scopeSubCategoriesCount(): int
    {
        return $this->child()->count();
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

}
