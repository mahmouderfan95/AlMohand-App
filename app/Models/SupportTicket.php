<?php

namespace App\Models;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SupportTicket extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_type',
        'customer_id',
        'title',
        'order_id',
        'details',
        'status'
    ];
    public function customer() : MorphTo
    {
        return $this->morphTo();
    }
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    public function supportTickets() : HasMany
    {
        return $this->hasMany(SupportTicketAttachment::class,'support_ticket_id');
    }
}
