<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $fillable = [
        'product_id',
        'language_id',
        'name',
        'desc',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'long_desc',
        'receipt_content',
    ];
    public $timestamps = false;

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
