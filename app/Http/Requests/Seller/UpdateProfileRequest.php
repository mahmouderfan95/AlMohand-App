<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'owner_name' => ['required','string'],
            'email' => ['nullable','email','string'],
            'phone' => ['nullable','string','max:11'],
            'logo' => 'nullable|image|mimes:png,jpg|max:2048',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'region_id' => 'nullable|exists:regions,id',
            'street' => 'nullable|string|max:255',
            'identity' => 'nullable|mimes:jpg,png,pdf,docx|max:2048',
            'commercial_register' => 'nullable|mimes:jpg,png,pdf,docx|max:2048',
            'tax_card' => 'nullable|mimes:jpg,png,pdf,docx|max:2048',
            'more' => 'nullable|mimes:jpg,png,pdf,docx|max:2048',
        ];
    }
}
