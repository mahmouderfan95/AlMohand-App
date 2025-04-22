<?php

namespace App\Models\POSTerminal;

use App\Models\BaseModel;
use App\Models\Distributor\DistributorPosTerminal;
use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $serial_number
 * @property string $software_version
 * @property string $status
 * @property string $balance
 * @property string $device_model
 * @property string $installation_date
 * @property string $secret_key
 * @property boolean $is_active
*/
class PosTerminal extends BaseModel
{
    use SoftDeletes, UuidDefaultValue;

    /**
     * @var string
     */
    protected $table = 'pos_terminal';
    public $incrementing = false;
    /**
     * @var string
     */
    /*protected $keyType = 'string';*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serial_number',
        'terminal_id',
        'software_version',
        'brand',
        'balance',
        'status',
        'name',
        'is_active',
    ];

    public function distributorPosTerminal()
    {
        return $this->hasOne(DistributorPosTerminal::class, 'pos_terminal_id', 'id');
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(PosTerminalTransaction::class);
    }
}
