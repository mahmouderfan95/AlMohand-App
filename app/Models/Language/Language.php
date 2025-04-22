<?php

namespace App\Models\Language;

use App\Enums\GeneralStatusEnum;
use App\Models\Category\CategoryTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{

    protected $fillable = ['name', 'code', 'locale', 'image', 'directory', 'status', 'sort_order'];

    public function getImageAttribute($value)
    {
        if (isset($value))
            return  asset('/storage/uploads/languages'). '/'.$value;
        return  asset('/images/no-image.png');
    }
    public function categoryTranslations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', GeneralStatusEnum::getStatusActive());
    }
}
