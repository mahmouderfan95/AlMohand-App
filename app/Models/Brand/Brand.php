<?php

namespace App\Models\Brand;

use App\Models\BaseModel;
use App\Models\Category\Category;
use App\Models\Category\CategoryBrand;
use App\Models\Order\OrderProduct;
use App\Models\Product\Product;
use App\Traits\TranslatedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends BaseModel
{
    use TranslatedAttributes;

    protected $fillable = ['status', 'image'];

    public $translatedAttributes = [
        'name',
        'description',
    ];

    protected $hidden = ['pivot'];



    public function images(): HasMany
    {
        return $this->hasMany(BrandImage::class);
    }

    public function translations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BrandTranslation::class);
    }

    public function category_brands(): HasMany
    {
        return $this->hasMany(CategoryBrand::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }


    public function getImageAttribute($value)
    {
        if (isset($value))
            return asset('/storage/uploads/brands') . '/' . $value;

        return asset('/images/no-image.png');
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }


}
