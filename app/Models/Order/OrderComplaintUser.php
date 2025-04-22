<?php

namespace App\Models\Order;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderComplaintUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_complaint_id',
    ];

    public function orderComplaint(): BelongsTo
    {
        return $this->belongsTo(OrderComplaint::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
