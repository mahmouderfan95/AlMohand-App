<?php

namespace App\Models\Distributor;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $distributor_group_id
 * @property string $condition_type
 * @property string $prefix
 * @property string $value
 **/
class DistributorGroupCondition extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'distributor_group_condition';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'distributor_group_id',
        'condition_type',
        'prefix',
        'value'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'distributor_group_id' => 'int',
        'condition_type' => 'string',
        'prefix' => 'string',
        'value' => 'string',
    ];
}
