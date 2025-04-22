<?php

namespace App\Models\BalanceRequest;

use App\Models\BaseModel;
use App\Models\Distributor\Distributor;
use App\Models\POSTerminal\PosTerminal;
use App\Models\POSTerminal\PosTerminalTransaction;
use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $transaction_id
 * @property string $distributor_id
 * @property string $pos_terminal_id
 * @property string $pos_name
 * @property string $amount
 * @property string $status
 * @property string $approved_by
*/
class BalanceRequest extends BaseModel
{
    use SoftDeletes, UuidDefaultValue;

    /**
     * @var string
     */
    protected $table = 'balance_request';
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
        'transaction_id',
        'distributor_id',
        'pos_terminal_id',
        'pos_name',
        'amount',
        'status',
        'approved_by',
        'code',
    ];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id', 'id');
    }

    public function posTerminal()
    {
        return $this->belongsTo(PosTerminal::class, 'pos_terminal_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(PosTerminalTransaction::class, 'transaction_id', 'id')->withDefault();
    }
}
