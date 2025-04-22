<?php

namespace App\Models;


use App\Models\Language\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SliderTranslation extends Model
{

    use HasFactory;

    protected $fillable = [
        'slider_id',
        'language_id',
        'title',
        'description',
        'image',
        'redirect_url',
        'alt_name',
    ];

    public $timestamps = false;

    public function getImageAttribute($value): string
    {
        if (isset($value))
            return asset('storage/uploads/sliders' . '/' . $value);

        return url("images/no-image.png");
    }



    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

}
