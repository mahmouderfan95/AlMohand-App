<?php

namespace App\Http\Requests\SalesRep\Auth;

use App\Http\Requests\BaseRequest;


class RegisterRequest extends BaseRequest
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
            'password' => 'required|string|min:4|max:20|confirmed',
            'username' => 'required|string|min:4|exists:sales_reps,username',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'The password is required.',
            'username.required' => 'The username is required.',
        ];
    }
}
