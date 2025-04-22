<?php

namespace App\Models\POSTerminal;

use App\Models\BalanceLog\BalanceLog;
use App\Models\BaseModel;
use App\Models\Distributor\Distributor;
use App\Models\Order\Order;
use App\Models\User;
use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $transaction_id
 * @property string $transaction_code
 * @property string $track_id
 * @property string $distributor_id
 * @property string $distributor_pos_terminal_id
 * @property string $order_id
 * @property string $amount
 * @property string $currency_code
 * @property string $exchange_rate
 * @property string $type
 * @property string $status
 * @property string $payment_method
 * @property string $description
 * @property string $reference_number
 * @property string $auth_id
 * @property object $transaction_object
 * @property object $transaction_date
 * @property string $created_by
 * @property string $created_by_type
 * @property string $updated_by
 * @property string $updated_by_type
 * @property double $balance_before
 * @property double $balance_after
*/
class PosTerminalTransaction extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'pos_terminal_transactions';

    public $incrementing = true;

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'transaction_code',
        'track_id',
        'track_id',
        'distributor_id',
        'pos_terminal_id',
        'distributor_pos_terminal_id',
        'order_id',
        'amount',
        'balance',
        'balance_before',
        'balance_after',
        'currency_code',
        'type',
        'status',
        'payment_method',
        'description',
        'reference_number',
        'auth_id',
        'created_by',
        'created_by_type',
        'updated_by',
        'transaction_object',
        'updated_by_type',
        'transaction_date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    public function pos_terminal(): BelongsTo
    {
        return $this->belongsTo(PosTerminal::class);
    }

    public function balanceLog(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BalanceLog::class,'transaction_id');
    }
}
