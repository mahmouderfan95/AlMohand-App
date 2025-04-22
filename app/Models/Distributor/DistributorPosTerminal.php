<?php

namespace App\Models\Distributor;

use App\Models\BaseModel;
use App\Models\Order\Order;
use App\Models\POSTerminal\PosTerminal;
use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property string $id
 * @property string $distributor_id
 * @property string $pos_terminal_id
 * @property string $otp
 * @property string $balance
 * @property string $points
 * @property string $branch_name
 * @property string $address
 * @property string $receiver_name
 * @property string $receiver_phone
 * @property string $activated_at
 * @property string $reset_at
 * @property string $is_active
 **/
class DistributorPosTerminal extends Authenticatable implements JWTSubject
{
    use SoftDeletes, UuidDefaultValue;

    /**
     * @var string
     */
    protected $table = 'distributor_pos_terminals';
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
        'distributor_id',
        'pos_terminal_id',
        'branch_name',
        'address',
        'otp',
        'balance',
        'points',
        'commission',
        'receiver_name',
        'receiver_phone',
        'serial_number',
        'password',
        'is_active',
    ];

    protected $hidden = ['otp'];

    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'ownerable');
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class, 'distributor_id', 'id');
    }

    public function posTerminal(): BelongsTo
    {
        return $this->belongsTo(PosTerminal::class, 'pos_terminal_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
