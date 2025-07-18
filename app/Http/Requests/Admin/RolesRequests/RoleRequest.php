<?php

namespace App\Http\Requests\Admin\RolesRequests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'name' => 'required|max:255',
            'display_name' => 'required|array',
            'display_name.*' => 'string',
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,name'
        ];
    }
}
