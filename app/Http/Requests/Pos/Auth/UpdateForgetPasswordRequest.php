<?php

namespace App\Http\Requests\Pos\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\Rules\Phone;

class UpdateForgetPasswordRequest extends FormRequest
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
        return [
            'phone' => ['required','regex:/^\d+$/', (new Phone)->country(config('services.laravel_phone.countries'))],    // :SA,EG
            'reset_token' => 'required|string',
            'new_password' => 'required|string',
        ];
    }
}
