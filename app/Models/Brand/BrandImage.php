<?php

namespace App\Models\Brand;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandImage extends Model
{

    protected $fillable = ['brand_id', 'key', 'image'];

    public $timestamps = false;

    public function getImageAttribute($value)
    {
        if (isset($value))
            return asset('/storage/uploads/brands') . '/' . $value;

        return asset('/images/no-image.png');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
