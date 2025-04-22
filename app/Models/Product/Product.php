<?php

namespace App\Models\Product;

use App\Models\Attribute\Attribute;
use App\Models\Brand\Brand;
use App\Models\Category\Category;
use App\Models\DirectPurchase;
use App\Models\Favorite;
use App\Models\Option\Option;
use App\Models\ValueAddedTax;
use App\Models\Vendor;
use App\Models\VendorProduct;
use App\Services\General\CurrencyService;
use App\Traits\TranslatedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use  TranslatedAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_id', 'type', 'vendor_id', 'serial', 'quantity', 'image', 'price', 'cost_price', 'points', 'status', 'sort_order', 'web', 'mobile',
        'sku', 'notify', 'minimum_quantity', 'max_quantity', 'wholesale_price', 'tax_id', 'packing_method', 'tax_type', 'tax_amount', 'is_live_integration', 'is_available'
    ];

    protected $appends = [
        'profit_rate'
    ];

    public $translatedAttributes = [
        'name',
        'desc',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'long_desc',
        'receipt_content',
        'content'
    ];

    public $append = [
        "is_fav",
    ];


    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }
    public function product_images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories')->withPivot('category_id', 'product_id');
    }
    public function productCategory(): HasOne
    {
        return $this->hasOne(ProductCategory::class);
    }



    public function productDiscountSellerGroup(): HasMany
    {
        return $this->hasMany(ProductDiscountSellerGroup::class);
    }

    public function vendorProducts(): HasMany
    {
        return $this->hasMany(VendorProduct::class);
    }



    public function productPriceDistributorGroups(): HasMany
    {
        return $this->hasMany(ProductPriceDistributorGroup::class);
    }

    public function productPriceSellerGroup(): HasMany
    {
        return $this->hasMany(ProductPriceSellerGroup::class)->with('seller_group');
    }

    public function productSerials(): HasMany
    {
        return $this->hasMany(ProductSerial::class);
    }

    public function valueAddedTax(): BelongsTo
    {
        return $this->belongsTo(ValueAddedTax::class);
    }
    // Define the relationship to favorites
    public function favorites() :HasMany
    {
        return $this->hasMany(Favorite::class,'product_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }


    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function attributes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute');
    }

    public function directPurchase(): HasOne
    {
        return $this->hasOne(DirectPurchase::class);
    }

    // Override the price attribute accessor
    public function getPriceAttribute($value)
    {
        $currency = CurrencyService::getCurrentCurrency();
        return $value * $currency->value;
    }

    public function getIsAvailableAttribute($value)
    {
        $isAvailable = $this->quantity < 1 ? 0 : 1;
        if (! $isAvailable) {
            $isAvailable = $this->directPurchase?->directPurchasePriorities->isNotEmpty() ? 1 : 0;
        }
        return $isAvailable;
    }

    public function starVotes()
    {
        return $this->ratings()
            ->select('product_id', 'stars', DB::raw('COUNT(*) as vote_count'))
            ->groupBy('product_id', 'stars');
    }


    public function getImageAttribute($value): string
    {
        if (isset($value) && $value != 'no-image.png'){
            return asset('/storage/uploads/products'). '/'.$value;
        }
        return asset('/storage/uploads/products'). '/product-card-placeholder.png';
    }


    public function scopeVendorFilter($query, $vendors)
    {
        $query->whereIn('vendor_id', $vendors);
    }

    public function scopeActive($query)
    {
        return $query->where('status', "active");
    }
    // Accessor to calculate profit_rate
    public function getProfitRateAttribute()
    {
        if ($this->wholesale_price && $this->price) {
            return ($this->price - $this->wholesale_price) / 100;
        }

        return null;
    }
}
