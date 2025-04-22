<?php

namespace App\Http\Requests\Admin\SalesRepLevelRequests;

use App\Enums\GeneralStatusEnum;
use App\Rules\AllLanguagesRequired;
use App\Rules\UniqueIgnoreSoftDeleted;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalesRepLevelRequest extends FormRequest
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
    public function rules(): array
    {
        $id = 0;

        if ($this->route('id'))
            $id = $this->route('id');

        return [
            'name' => ['required', 'array', new AllLanguagesRequired()],
            'name.*' => [
                'required',
                'string',
                'min:2',
                'max:191',
                new UniqueIgnoreSoftDeleted('sales_rep_levels', 'sales_rep_level_translations', 'name', 'sales_rep_level_id', $id)
            ],
            'status' => ['required', Rule::in(GeneralStatusEnum::getList())],
            'code' => ['required', 'string', 'max:255', Rule::unique('sales_rep_levels', 'code')->ignore($id)],
            'parent_id' => ['nullable', 'exists:sales_rep_levels,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ];
    }

    /*
    public function messages()
    {

        return [
            'name.*.required' => trans("validation.required"),
            "description.required" => trans("validation.required"),
        ];

    }
    */
}
