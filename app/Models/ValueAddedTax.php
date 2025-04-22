<?php

namespace App\Models;


use App\Enums\GeneralStatusEnum;
use App\Models\GeoLocation\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValueAddedTax extends Model
{

    use SoftDeletes, HasFactory;

    protected $fillable = [
        'country_id',
        'tax_rate',
        'status',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', GeneralStatusEnum::getStatusActive());
    }

}
