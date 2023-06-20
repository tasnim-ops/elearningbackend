<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'course_description' => 'string|max:300',
            'category_id' => 'required|exists:categories,id',
            'teacher_id' => 'required|exists:teachers,id',
            'price' => 'required|numeric',
            'documents' => 'array',
            'documents.*' => 'file|mimes:pdf,mp4|max:2048',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Perform any additional validation or modifications here
        });
    }
}
