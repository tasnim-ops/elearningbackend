<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UtilisatorRequest extends FormRequest
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
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required|string|min:8',
            'photo' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'fb' => 'url',
            'linkedin'=>'url',
            'github'=>'url',
            'desc'=> 'string|max:255'

        ];
    }

}
