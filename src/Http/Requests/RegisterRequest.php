<?php

namespace Litepie\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Registration is open to everyone
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
            
            // Profile fields (if enabled in config)
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            
            // Terms and conditions
            'terms_accepted' => ['required', 'accepted'],
            'privacy_accepted' => ['required', 'accepted'],
            'marketing_emails' => ['sometimes', 'boolean'],
            
            // Captcha (if enabled)
            'captcha' => config('users.registration.captcha_enabled') ? ['required'] : ['sometimes'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_type' => 'user type',
            'first_name' => 'first name',
            'last_name' => 'last name',
            'birth_date' => 'birth date',
            'terms_accepted' => 'terms and conditions',
            'privacy_accepted' => 'privacy policy',
            'marketing_emails' => 'marketing emails',
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
            'terms_accepted.accepted' => 'You must accept the terms and conditions.',
            'privacy_accepted.accepted' => 'You must accept the privacy policy.',
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
        ]);
    }
}
