<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:companies,name',
            'address' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'website' => 'nullable|url|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'owner_password' => 'required|string|min:8',
            'owner_confirm_password' => 'required|string|min:8|same:owner_password',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.unique' => 'This company name has already been taken.',
            'name.max' => 'The Company name must be less than 255 characters',
            'name.string' => 'The Company name must be a string',

            'address.required' => 'The address field is required.',
            'address.max' => 'The address must be less than 255 characters',
            'address.string' => 'The address must be a string',

            'industry.required' => 'The industry field is required.',
            'industry.max' => 'The industry must be less than 255 characters',
            'industry.string' => 'The industry must be a string',
            
            'website.url' => 'The website must be a valid URL',
            'website.max' => 'The website must be less than 255 characters',
            'website.string' => 'The website must be a string',

            'owner_name.required' => 'The owner name field is required.',
            'owner_name.max' => 'The owner name must be less than 255 characters',
            'owner_name.string' => 'The owner name must be a string',

            'owner_password.required' => 'The owner password field is required.',
            'owner_password.min' => 'The owner password must be at least 8 characters',
            'owner_password.string' => 'The owner password must be a string',

            'owner_confirm_password.required' => 'The owner confirm password field is required.',
        ];
    }
    
    
}
