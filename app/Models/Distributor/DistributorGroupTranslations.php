<?php

namespace App\Models\Distributor;

use App\Models\BaseModel;

/**
 * @property string $id
 * @property string $distributor_id
 * @property int $language_id
 * @property string $name
 * @property string $commercial_register_name
**/
class DistributorGroupTranslations extends BaseModel
{
    protected $table = 'distributor_group_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'distributor_group_id',
        'language_id',
        'name',
        'description'
    ];
}
