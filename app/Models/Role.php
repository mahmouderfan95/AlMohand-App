<?php

namespace App\Models;

use App\Enums\GeneralStatusEnum;
use App\Traits\TranslatedAttributes;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends SpatieRole
{
    use TranslatedAttributes;

    public $translatedAttributes = ['display_name'];


    public function translations(): HasMany
    {
        return $this->hasMany(RoleTranslation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', GeneralStatusEnum::getStatusActive());
    }



}
