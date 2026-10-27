<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Company;

class CompanyUpdateRequest extends FormRequest
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
        $companyId = $this->route('company');
        $company = $companyId
            ? Company::findOrFail($companyId)
            : Company::where('owner_id', $this->user()->id)->firstOrFail();

        return [
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'address' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'website' => 'nullable|url|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email,' . $company->owner_id,
            'owner_password' => 'nullable|string|min:8',
            'owner_confirm_password' => 'nullable|string|min:8|same:owner_password',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.unique' => 'This company name has already been taken.',
            'name.max' => 'The company name must be less than 255 characters',
            'name.string' => 'The company name must be a string',

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
            'owner_confirm_password.min' => 'The owner confirm password must be at least 8 characters',
            'owner_confirm_password.string' => 'The owner confirm password must be a string',
            'owner_confirm_password.same' => 'The owner confirm password and owner password must match',
        ];
    }
}