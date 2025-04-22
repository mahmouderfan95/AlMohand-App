<?php

namespace App\Models\GeoLocation;

use App\Models\Vendor;
use App\Traits\TranslatesName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use TranslatesName;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'flag',
        'vat',
        'code',
    ];

    protected $appends = ['name'];


    public function translations(): HasMany
    {
        return $this->hasMany(CountryTranslation::class);
    }

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    public function cities()
    {
        return $this->hasManyThrough(City::class, Region::class);
    }
    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }


    public function getFlagAttribute($value): string
    {
        if (isset($value))
            return asset('storage/uploads/countries' . '/' . $value);

        return url("images/no-image.png");
    }

}
