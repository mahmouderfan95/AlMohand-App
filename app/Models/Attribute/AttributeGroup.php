<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeGroup extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','status',
    ];
    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
    public function translations(): HasMany
    {
        return $this->hasMany(AttributeGroupTranslation::class);
    }


}
