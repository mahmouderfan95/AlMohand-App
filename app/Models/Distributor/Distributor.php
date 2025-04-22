<?php

namespace App\Models\Distributor;

use App\Models\BaseModel;
use App\Models\GeoLocation\City;
use App\Models\GeoLocation\Region;
use App\Models\GeoLocation\Zone;
use App\Models\POSTerminal\PosTerminalTransaction;
use App\Models\SalesRep\SalesRep;
use App\Traits\TranslatedAttributes;
use App\Traits\TranslatesName;
use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property int $distributor_group_id
 * @property int $sales_rep_id
 * @property int $zone_id
 * @property int $city_id
 * @property int $region_id
 * @property string $code
 * @property string $logo
 * @property string $manager_name
 * @property string $owner_name
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $location
 * @property string $commercial_register
 * @property string $tax_card
 * @property int $points
 * @property double $balance
 * @property double $commission_balance
 * @property double $pos_terminals_count
 * @property int $is_active
**/
class Distributor extends BaseModel
{
    use TranslatesName, SoftDeletes, TranslatedAttributes, UuidDefaultValue;

    /**
     * @var string
     */
    protected $table = 'distributor';

    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var string
     */
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'distributor_group_id',
        'zone_id',
        'city_id',
        'region_id',
        'sales_rep_id',
        'code',
        'logo',
        'manager_name',
        'owner_name',
        'phone',
        'email',
        'address',
        'location',
        'balance',
        'commercial_register',
        'tax_card',
        'is_active',
    ];

    /**
     * @var string[]
     */
    public $translatedAttributes = [
        'name',
        'commercial_register_name',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'id' => 'string',
    ];

    /**
     * @return HasMany
     */
    public function translations(): HasMany
    {
        return $this->hasMany(DistributorTranslations::class, 'distributor_id', 'id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(DistributorAttachments::class, 'distributor_id', 'id');
    }
    public function posTerminals(): HasMany
    {
        return $this->hasMany(DistributorPosTerminal::class, 'distributor_id', 'id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PosTerminalTransaction::class);
    }
    public function group(): BelongsTo
    {
        return $this->belongsTo(DistributorGroup::class, 'distributor_group_id', 'id');
    }
    public function sales_rep(): BelongsTo
    {
        return $this->belongsTo(SalesRep::class, 'sales_rep_id', 'id');
    }

    public function getLogoAttribute($value): string
    {
        if (isset($value))
            return asset('storage/uploads/distributors' . '/' . $value);

        return url("images/no-image.png");
    }

}
