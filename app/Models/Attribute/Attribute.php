<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'attribute_group_id',
    ];
    public function group()
    {
        return $this->belongsTo(AttributeGroup::class, "attribute_group_id");
    }
    public function translations(): HasMany
    {
        return $this->hasMany(AttributeTranslation::class);
    }


}
