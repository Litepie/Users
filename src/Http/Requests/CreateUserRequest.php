<?php

namespace Litepie\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
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
            'user_type' => ['required', 'string', Rule::in(config('users.user_types', []))],
            'status' => ['sometimes', 'string', Rule::in(['active', 'inactive', 'pending', 'suspended', 'banned'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'locale' => ['nullable', 'string', 'max:10'],
            
            // Profile fields
            'profile.first_name' => ['nullable', 'string', 'max:255'],
            'profile.last_name' => ['nullable', 'string', 'max:255'],
            'profile.bio' => ['nullable', 'string', 'max:1000'],
            'profile.website' => ['nullable', 'url', 'max:255'],
            'profile.location' => ['nullable', 'string', 'max:255'],
            'profile.birth_date' => ['nullable', 'date', 'before:today'],
            'profile.gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'profile.company' => ['nullable', 'string', 'max:255'],
            'profile.job_title' => ['nullable', 'string', 'max:255'],
            
            // Role assignment
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_type' => 'user type',
            'profile.first_name' => 'first name',
            'profile.last_name' => 'last name',
            'profile.birth_date' => 'birth date',
            'profile.job_title' => 'job title',
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
            'user_type.in' => 'The selected user type is invalid.',
            'profile.birth_date.before' => 'Birth date must be before today.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email),
        ]);
    }
}
