<?php

namespace App\Models\BalanceLog;

use App\Models\BaseModel;
use App\Models\Distributor\Distributor;
use App\Models\POSTerminal\PosTerminal;
use App\Models\POSTerminal\PosTerminalTransaction;
use App\Models\SalesRep\SalesRep;
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
class BalanceLog extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'balance_log';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'distributor_id',
        'pos_terminal_id',
        'balance_type',
        'transaction_type',
        'balance_before',
        'balance_after',
        'sales_rep_id',
    ];

    public $timestamps = true;

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(PosTerminalTransaction::class);
    }

    public function salesRep(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesRep::class);
    }

    public function posTerminal()
    {
        return $this->belongsTo(PosTerminal::class, 'pos_terminal_id', 'id');
    }
}
