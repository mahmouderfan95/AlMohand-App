<?php

namespace App\Models\GeoLocation;

use App\Models\BaseModel;
use App\Traits\TranslatesName;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends BaseModel
{

    use TranslatesName, SoftDeletes;

    protected $table = 'zone';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id',
    ];

    protected $appends = ['name'];

    /*public $translatedAttributes = [
        'name'
    ];*/

    public function translations(): HasMany
    {
        return $this->hasMany(ZoneTranslations::class);
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
