<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class favorite extends Model
{
    use HasFactory;
    protected $fillable = ['product_id'];

    // Define the polymorphic relationship
    public function favoritable() : MorphTo
    {
        return $this->morphTo();
    }

    // Define the relationship to the product
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
