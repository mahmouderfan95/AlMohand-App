<?php

namespace App\Models\SalesRepLevel;

use App\Models\SalesRep\SalesRep;
use App\Traits\TranslatesName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesRepLevel extends Model
{
    use HasFactory, TranslatesName, SoftDeletes;

    protected $fillable = ['status', 'code', 'parent_id', 'permissions'];
    protected $appends = ['name'];

    protected $casts = [
        'permissions' => 'array',
    ];
    /**
     * Get the translations for the user level.
     */
    public function translations()
    {
        return $this->hasMany(SalesRepLevelTranslation::class);
    }

    /**
     * Get the translations for the user level.
     */
    public function sales_reps()
    {
        return $this->hasMany(SalesRep::class);
    }

    /**
     * Get the parent user level.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the child user levels.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
