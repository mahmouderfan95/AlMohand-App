<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerAttachment extends Model
{
    protected $table = 'seller_attachments';
    protected $fillable = [
        'seller_id',
        'file_url',
        'type',
        'extension',
        'size',
    ];

    public $timestamps = false;
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function getFileUrlAttribute($value): string
    {
        if (isset($value))
            return asset('storage/uploads/sellers' . '/' . $value);

        return url("images/no-image.png");
    }





}
