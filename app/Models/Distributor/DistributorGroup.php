<?php

namespace App\Models\Distributor;

use App\Models\BaseModel;
use App\Traits\TranslatedAttributes;
use App\Traits\TranslatesName;
use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property boolean $is_active
 * @property boolean $is_auto_assign
 * @property boolean $is_require_all_conditions
 **/
class DistributorGroup extends BaseModel
{
    use TranslatesName, SoftDeletes, TranslatedAttributes;

    /**
     * @var string
     */
    protected $table = 'distributor_group';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_require_all_conditions',
        'is_auto_assign',
        'is_active',
    ];

    /**
     * @var string[]
     */
    public array $translatedAttributes = [
        'name',
        'description',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'id' => 'int',
        'is_active' => 'boolean',
        'is_auto_assign' => 'boolean',
        'is_require_all_conditions' => 'boolean',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['name'];


    /**
     * @return HasMany
     */
    public function translations(): HasMany
    {
        return $this->hasMany(DistributorGroupTranslations::class);
    }

    public function conditions(): HasMany
    {
        return $this->hasMany(DistributorGroupCondition::class);
    }

}
