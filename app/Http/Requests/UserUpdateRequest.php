<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UserUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'bail|required|string|min:8',
            'confirm_password' => 'bail|required|string|min:8|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'The password field is required',
            'password.min' => 'The password must be at least 8 characters long',
            'confirm_password.required' => 'The confirm password field is required',
            'confirm_password.min' => 'The confirm password must be at least 8 characters long',
            'confirm_password.same' => 'The confirm password and password must match',
        ];
    }
}