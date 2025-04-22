<?php

namespace App\Models\SalesRepLevel;

use App\Models\Language\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesRepLevelTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['sales_rep_level_id', 'language_id', 'name'];

    /**
     * Get the user level associated with the translation.
     */
    public function sales_rep_level()
    {
        return $this->belongsTo(SalesRepLevel::class, 'sales_rep_level_id');
    }


    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
