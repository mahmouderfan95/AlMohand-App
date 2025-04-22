<?php

namespace App\Models\SalesRep;

use App\Models\GeoLocation\City;
use App\Models\GeoLocation\Region;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesRepLocation extends Model
{
    use HasFactory;

    protected $fillable = ['city_id', 'region_id', 'sales_rep_id'];

    /**
     * Get the city associated with the location.
     */
    public function sales_rep()
    {
        return $this->belongsTo(SalesRep::class);
    }
    /**
     * Get the city associated with the location.
     */

    /**
     * Get the city associated with the location.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the region associated with the location.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
