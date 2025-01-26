<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'content' => ['required', 'string', 'min:10', 'max:1000'],
            'rating' => ['required', 'numeric', 'min:1', 'max:5']
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
            'content.required' => 'The content field is required.',
            'content.string' => 'The content field must be a string.',
            'content.min' => 'The content field must be at least :min characters.',
            'content.max' => 'The content field must not exceed :max characters.',
            'rating.required' => 'The rating field is required.',
            'rating.numeric' => 'The rating field must be a number.',
            'rating.min' => 'The rating field must be at least :min.',
            'rating.max' => 'The rating field must not exceed :max.'
        ];
    }
}
