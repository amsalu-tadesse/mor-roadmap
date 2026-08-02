<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MobileNumber;
class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'mobile' => ['required', new MobileNumber, Rule::unique('users', 'mobile')],
            'user_roles' => 'nullable|array',
            'user_roles.*' => 'exists:roles,id',
            'is_superadmin' => '',
            'organization_id' => 'nullable|exists:organizations,id',
            'twofa_code' => '',
            'status' => '',
        ];

        if ($this->routeIs('auth.signup')) {
            $rules['terms'] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'mobile.unique' => 'This mobile number is already registered.',
            'mobile.required' => 'The mobile number field is required.',
            'terms.required' => 'You must agree to the terms and conditions.',
        ];
    }
}
