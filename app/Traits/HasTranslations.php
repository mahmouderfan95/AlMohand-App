<?php

namespace App\Traits;

trait HasTranslations
{
    public function getTranslation($translations, string $column)
    {
        $lang = (request()?->header('lang') ?? 'en') === 'ar' ? 'ar' : 'en';

        $languageId = match ($lang) {
            'en' => 1,
            default => 2, // Arabic
        };

        $translation = collect($translations)->first(function ($item) use ($languageId) {
            return (int) $item['language_id'] === $languageId;
        });

        return $translation[$column] ?? null;
    }
}
