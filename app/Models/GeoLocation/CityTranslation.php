<?php

namespace App\Models\GeoLocation;

use Illuminate\Database\Eloquent\Model;

class CityTranslation extends Model
{
    protected $fillable = [
        'city_id',
        'language_id',
        'name',
    ];
    public $timestamps = false;

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
