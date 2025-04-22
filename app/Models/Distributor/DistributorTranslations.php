<?php

namespace App\Models\Distributor;

use App\Models\BaseModel;
use App\Traits\UuidDefaultValue;

/**
 * @property string $id
 * @property string $distributor_id
 * @property int $language_id
 * @property string $name
 * @property string $commercial_register_name
**/
class DistributorTranslations extends BaseModel
{
    use UuidDefaultValue;

    protected $table = 'distributor_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'distributor_id',
        'language_id',
        'name',
        'commercial_register_name'
    ];
}
