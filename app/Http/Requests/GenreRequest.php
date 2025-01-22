<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:6', 'max:30', Rule::unique('genres')->ignore($this->genre)],
            'description' => ['required', 'string', 'min:6', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.min' => 'The name field must be at least :min characters.',
            'name.max' => 'The name field may not be greater than :max characters.',
            'name.unique' => 'The name field already exists.',
            'description.required' => 'The description field is required.',
            'description.min' => 'The description field must be at least :min characters.',
            'description.string' => 'The description field must be a string.',
            'description.max' => 'The description field may not be greater than :max characters.',
        ];
    }
}
