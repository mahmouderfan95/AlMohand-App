<?php

namespace App\Models\Merchant;

use App\Models\BaseModel;
use App\Models\Option\OptionValue;
use App\Traits\TranslatedAttributes;
use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerchantLogins extends BaseModel
{
    protected $table = 'merchant_logins';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_id', 'platform', 'os_version', 'app_version', 'mobile_brand', 'ip'
    ];
}
