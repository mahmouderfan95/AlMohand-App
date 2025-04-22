<?php

namespace App\Models\Currency;

use App\Traits\TranslatedAttributes;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use TranslatedAttributes;

    protected $fillable = ['value', 'status', 'decimal_place', 'is_default'];

    public $translatedAttributes = [
        'name',
        'code',
    ];

    public function translations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CurrencyTranslation::class);
    }

}
