<?php

namespace App\Http\Resources\Admin;

use App\Models\Language\Language;
use App\Traits\HasTranslations;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseAdminResource extends JsonResource
{

    use HasTranslations;

    public function formatTranslations($translations, $foreign_key): array
    {
        $formatted = [];

        if (!empty($translations)) {

            foreach ($translations as $translation) {
                $language_code = Language::query()->where('id', $translation->language_id)->select('id', 'code')->first()?->code;

                $excluded_fields = ['id', 'language_id', $foreign_key, 'created_at', 'updated_at'];

                $formatted_columns = collect($translation->getAttributes())->except($excluded_fields)->toArray();

                $formatted[$language_code] = $formatted_columns;
            }

        }

        return $formatted;
    }

    public function getApiLang(): array|string|null
    {
        $lang = request()->header("lang");
        if (empty($lang) || !in_array($lang, ["en", "ar"])){
            $lang = "ar";
        }
        return $lang;
    }
}
