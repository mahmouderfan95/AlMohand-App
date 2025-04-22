<?php

namespace App\Rules;

use App\Models\Category\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RequiredProductOption implements ValidationRule
{
    protected $categoryIds;

    public function __construct($categoryIds)
    {
        $this->categoryIds = $categoryIds;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the category contains any top-up categories
        $haveTopUp = Category::whereIn('id', $this->categoryIds)->where('is_topup', 1)->exists();

        // If there is a top-up category and the value is empty, fail the validation
        if ($haveTopUp && empty($value)) {
            $fail(__('validation.required', ['attribute' => $attribute]));
        }
    }
}
