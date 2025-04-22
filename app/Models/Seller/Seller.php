<?php

namespace App\Models\Seller;

use App\Enums\GeneralStatusEnum;
use App\Models\Cart\Cart;
use App\Models\Favorite;
use App\Models\Order\Order;
use App\Models\PointsHistory;
use App\Models\SellerGroup\SellerGroup;
use App\Models\SellerGroup\SellerGroupLevel;
use App\Models\SupportTicket;
use App\Models\User;
use App\Traits\TranslatedAttributes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Seller extends Authenticatable implements JWTSubject
{
    use SoftDeletes, TranslatedAttributes,Notifiable;

    protected $table = 'sellers';

    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'owner_name',
        'email',
        'password',
        'status',
        'approval_status',
        'logo',
        'phone',
        'balance',
        'enable_google2fa',
        'google2fa_secret',
        'address_details',
        'seller_group_id',
        'seller_group_level_id',
        'otp'
    ];

    public $translatedAttributes = ['reject_reason'];

    protected $appends = ['approval_status_form'];

    public function orders()
    {
        return $this->morphMany(Order::class, 'ownerable');
    }

    public function cart()
    {
        return $this->morphMany(Cart::class, 'ownerable');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SellerTranslations::class);
    }
    public function seller_transactions(): HasMany
    {
        return $this->hasMany(SellerTransaction::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sellerGroup(): BelongsTo
    {
        return $this->belongsTo(SellerGroup::class, 'seller_group_id');
    }
    public function sellerGroupLevel(): BelongsTo
    {
        return $this->belongsTo(SellerGroupLevel::class, 'seller_group_level_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function sellerAttachment(): HasMany
    {
        return $this->hasMany(SellerAttachment::class, 'seller_id');
    }

    public function sellerAddress(): HasOne
    {
        return $this->hasOne(SellerAddress::class);
    }
    // Define the polymorphic relationship to favorites
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
    public function carts() : MorphMany
    {
        return $this->morphMany(Cart::class,'owner');
    }
    public function pointsHistory()
    {
        return $this->morphMany(PointsHistory::class, 'pointable');
    }

    public function SupportTickets() : MorphMany
    {
        return $this->morphMany(SupportTicket::class,'customer');
    }
    public function getLogoAttribute($value): string
    {
        if (isset($value))
            return asset('storage/uploads/sellers' . '/' . $value);

        return url("images/no-image.png");
    }

    protected function getApprovalStatusFormAttribute()
    {
        return $this->approval_status ? __('constants.' . $this->approval_status) : '';
    }



    public function scopeActive($query)
    {
        return $query->where('status', GeneralStatusEnum::getStatusActive());
    }



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected function getDefaultGuardName(): string { return 'sellerApi'; }

}
