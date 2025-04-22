<?php

namespace App\Http\Requests\Admin\SalesRepUserRequests;

use App\Enums\GeneralStatusEnum;
use App\Rules\AllLanguagesRequired;
use App\Rules\UniqueIgnoreSoftDeleted;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalesRepUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique('sales_reps', 'name')->ignore($id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('sales_reps', 'username')->ignore($id)],
            'code' => ['required', 'string', 'max:255', Rule::unique('sales_reps', 'code')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:255', Rule::unique('sales_reps', 'phone')->ignore($id)],
            'email' => ['nullable', 'string', 'max:255', Rule::unique('sales_reps', 'email')->ignore($id)],
            'status' => ['nullable', Rule::in(GeneralStatusEnum::getList())],
            'parent_id' => ['nullable', 'exists:sales_reps,id'],
            'sales_rep_level_id' => ['required', 'exists:sales_rep_levels,id'],
            'locations' => ['nullable', 'array'],
            'locations.*.city_id' => ['required_with:locations', 'exists:cities,id'],
            'locations.*.region_id' => ['required_with:locations', 'exists:regions,id'],
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
