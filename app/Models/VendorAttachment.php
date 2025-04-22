<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorAttachment extends Model
{
    protected $fillable = [
        'vendor_id',
        'file_url',
        'extension',
        'size',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getFileUrlAttribute($value): string|null
    {
        if (isset($value))
            return asset('storage/uploads/vendors' . '/' . $value);

        return null;
    }





}
