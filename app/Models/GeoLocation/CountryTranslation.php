<?php

namespace App\Models\GeoLocation;

use Illuminate\Database\Eloquent\Model;

class CountryTranslation extends Model
{
    protected $fillable = [
        'country_id',
        'language_id',
        'name',
    ];
    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
