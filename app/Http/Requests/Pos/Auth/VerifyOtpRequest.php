<?php

namespace App\Http\Requests\Pos\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\Rules\Phone;

class VerifyOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
        if (auth('merchantApi')->check()) {
            return [
                'otp' => 'required|string|max:6',
            ];
        } else {
            return [
                'phone' => ['required',(new Phone)->country(config('services.laravel_phone.countries'))],
                'otp' => 'required|string',
                'device_id' => 'nullable|string',
            ];
        }
    }
}
