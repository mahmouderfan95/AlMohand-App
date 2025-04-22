<?php

namespace App\Http\Requests\Pos\Auth;

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
            'otp' => 'required|string|min:4|max:8',
            'serial_number' => 'required|string|min:6|exists:distributor_pos_terminals,serial_number',
            'ip_address' => 'nullable|ip',
            'app_version' => 'nullable|string',
            'location' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'The password is required.',
            'device_id.required' => 'The device ID is required.',
            'ip_address.ip' => 'The IP address must be a valid IP.',
        ];
    }
}
