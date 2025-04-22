<?php

namespace App\Models\SalesRep;

use App\Models\BaseModel;
use App\Models\Distributor\Distributor;
use App\Models\POSTerminal\PosTerminal;
use App\Models\SalesRep\SalesRep;
use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\SoftDeletes;


class SalesRepTransaction extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'sales_rep_transactions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'balance_type',
        'transaction_type',
        'balance_before',
        'balance_after',
        'sales_rep_id',
        'approved_by',
    ];

    public $timestamps = true;


    public function salesRep(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesRep::class);
    }


    public function approvedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesRep::class, 'approved_by');
    }

}
