<?php

namespace App\Models\Order;


use App\Models\BaseModel;
use App\Models\Currency\Currency;
use App\Models\FailedOrderReason;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status',
        'owner_type',
        'owner_id',
        'currency_id',
        'payment_method',
        'real_amount',
        'total',
        'sub_total',
        'vat',
        'tax',
        'order_source',
    ];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userPulled(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, OrderUser::class, 'order_id', 'id', 'id', 'user_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function order_products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function orderProduct(): HasOne
    {
        return $this->hasOne(OrderProduct::class);
    }

    public function orderGift(): HasOne
    {
        return $this->hasOne(OrderGift::class);
    }

    public function failedReasons()
    {
        return $this->hasMany(FailedOrderReason::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(OrderPaymentTransaction::class);
    }

    public function order_histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class,'order_id');
    }
}
