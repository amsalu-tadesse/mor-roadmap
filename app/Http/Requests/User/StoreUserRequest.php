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
        return [

            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            // 'mobile' => [ Rule::unique('users', 'mobile')],
            // 'mobile' => [ 'required',new MobileNumber, Rule::unique('users', 'mobile')],
            'mobile' => [ new MobileNumber, Rule::unique('users', 'mobile')],
            'user_roles' =>[Rule::exists('roles', 'id')],
            'is_superadmin' =>'',
            'organization_id' =>'',
            'twofa_code' =>'',
            'status' => '',
        ];
    }
}
