<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovieRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:6', 'max:60', Rule::unique('movies')->ignore($this->movie)],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
            'cover_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'release_date' => ['required', 'date', 'before:today', 'date_format:d-m-Y'],
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
            'title.required' => 'The title field is required.',
            'title.string' => 'The title field must be a string.',
            'title.min' => 'The title field must be at least :min characters.',
            'title.max' => 'The title field must not be greater than :max characters.',
            'title.unique' => 'The title has already been taken.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description field must be a string.',
            'description.min' => 'The description field must be at least :min characters.',
            'description.max' => 'The description field must not be greater than :max characters.',
            'cover_image.required' => 'The cover image field is required.',
            'cover_image.image' => 'The cover image field must be an image.',
            'cover_image.mimes' => 'The cover image field must be a file of type: jpeg, png, jpg, gif, svg.',
            'cover_image.max' => 'The cover image field must not be greater than :max kilobytes.',
            'release_date.required' => 'The release date field is required.',
            'release_date.date' => 'The release date field must be a date.',
            'release_date.before' => 'The release date field must be a date before today.',
            'release_date.date_format' => 'The release date field must be in the format: d-m-Y.',
        ];
    }
}
