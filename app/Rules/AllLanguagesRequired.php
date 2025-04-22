<?php

namespace App\Rules;

use App\Enums\GeneralStatusEnum;
use App\Models\Language\Language;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllLanguagesRequired implements ValidationRule
{
    protected $languageIds;
    public function __construct()
    {
        $this->languageIds = Language::where('status', GeneralStatusEnum::getStatusActive())->pluck('id')->toArray();
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($this->languageIds as $id) {
            if (!array_key_exists($id, $value)) {
                $fail(__('validation.all_languages_required', ['id' => $id]));
            }
        }
    }
}
