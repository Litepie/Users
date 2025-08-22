<?php

namespace Litepie\Users\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // API registration is open
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['sometimes', 'string', Rule::in(config('users.registration.allowed_types', ['regular']))],
            'phone' => ['nullable', 'string', 'max:20'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'locale' => ['nullable', 'string', 'max:10'],
            
            // Profile fields
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            
            // Device information for API registration
            'device_name' => ['sometimes', 'string', 'max:255'],
            'device_type' => ['sometimes', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.',
            'user_type.in' => 'The selected user type is not allowed for registration.',
            'birth_date.before' => 'Birth date must be before today.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email ?? ''),
            'user_type' => $this->user_type ?? 'regular',
            'device_name' => $this->device_name ?? $this->header('User-Agent', 'Unknown Device'),
        ]);
    }
}
