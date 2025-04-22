<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SupportTicketAttachment extends Model
{
    use HasFactory;
    protected $fillable = ['support_ticket_id','file_url','extension','size'];
    public function supportTicket() : BelongsToMany
    {
        return $this->belongsToMany(SupportTicket::class,'support_ticket_id');
    }
}
