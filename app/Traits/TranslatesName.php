<?php
namespace App\Traits;

use App\Models\Language\Language;

trait TranslatesName
{

    public function getNameAttribute($value)
    {
        $local = app()->getLocale() ??'ar';
        // dd(Language::where('code',$local)->first());
        $lang_id =   session('client_lang') ?? Language::where('code',$local)->first()->id;
        return $this->translations()->where('language_id', $lang_id)->first()?->name;
    }
}
