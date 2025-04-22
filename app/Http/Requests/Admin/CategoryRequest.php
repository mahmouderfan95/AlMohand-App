<?php

namespace App\Http\Requests\Admin;

use App\Rules\AllLanguagesRequired;
use App\Rules\UniqueIgnoreSoftDeleted;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = 0;

        if ($this->route('id'))
            $id = $this->route('id');

        return [
            'name' => ['required','array',new AllLanguagesRequired()],
            'name.*' => [
                'required',
                'string',
                'min:2',
                'max:191',
                new UniqueIgnoreSoftDeleted('categories', 'category_translations', 'name', 'category_id', $id)
                // Rule::unique('category_translations', 'name')->ignore($id,'category_id')
            ],
            'parent_id' => 'nullable|numeric|exists:categories,id',
            'image' => 'image|max:1048576',
            // 'status' => 'required|in:active,inactive',
            // 'meta_title' => ['required','array',new AllLanguagesRequired()],
            // 'meta_title.*' => ['required','string','min:2','max:191'],
            // 'web' => 'required|boolean',
            // 'mobile' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.*.required' => trans("admin.categories.validations.name_ar_required"),
            "parent_id.numeric" => trans("admin.categories.validations.parent_id_numeric"),
            "parent_id.exists" => trans("admin.categories.validations.parent_id_exists"),
        ];
    }
}
