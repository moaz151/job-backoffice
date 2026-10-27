<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class JobVacancyCreateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'type' => 'required|string|in:full-time,part-time,contract,remote,hybrid',
            'company_id' => 'required|uuid|exists:companies,id',
            'category_id' => 'required|uuid|exists:job_categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title must be less than 255 characters.',

            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a string.',

            'location.required' => 'The location field is required.',
            'location.string' => 'The location must be a string.',
            'location.max' => 'The location must be less than 255 characters.',
            
            'salary.required' => 'The salary field is required.',
            'salary.numeric' => 'The salary must be a number.',
            'salary.min' => 'The salary must be greater than 0.',

            'type.required' => 'The type field is required.',
            'type.string' => 'The type must be a string.',
            'type.in' => 'The type must be one of the following: full-time, part-time, contract, remote, hybrid.',

            'company_id.required' => 'The company field is required.',
            'company_id.uuid' => 'The company must be a valid UUID.',
            'company_id.exists' => 'The company must exist in the database.',

            'category_id.required' => 'The category field is required.',
            'category_id.uuid' => 'The category must be a valid UUID.',
            'category_id.exists' => 'The category must exist in the database.',
        ];
    }
    
    
}
