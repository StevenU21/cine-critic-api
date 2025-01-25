<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DirectorRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'biography' => ['required', 'string', 'min:3', 'max:2000'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:4096'],
            'birth_date' => ['required', 'date', 'before:today', 'after:01-01-1890', 'date_format:d-m-Y'],
            'nationality' => ['required', 'string', 'max:50'],
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
            'biography.required' => 'The biography field is required.',
            'biography.string' => 'The biography field must be a string.',
            'biography.min' => 'The biography field must be at least :min characters.',
            'biography.max' => 'The biography field may not be greater than :max characters.',
            'image.required' => 'The image field is required.',
            'image.string' => 'The image field must be a string.',
            'image.image' => 'The image field must be an image.',
            'image.mimes' => 'The image field must be a file of type: :values.',
            'image.max' => 'The image field may not be greater than :max kilobytes.',
            'birth_date.required' => 'The birth date field is required.',
            'birth_date.date' => 'The birth date field must be a date.',
            'birth_date.before' => 'The birth date field must be a date before today.',
            'birth_date.after' => 'The birth date field must be a date after 01-01-1890.',
        ];
    }
}
