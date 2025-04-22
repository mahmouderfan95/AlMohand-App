<?php

namespace App\Models\GeoLocation;

use Illuminate\Database\Eloquent\Model;

class ZoneTranslations extends Model
{
    protected $fillable = [
        'region_id',
        'language_id',
        'name',
    ];
    public $timestamps = false;
}
