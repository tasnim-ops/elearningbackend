<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdministratorRequest extends FormRequest
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
        $userId = $this->route('utilisateur'); // Récupère l'ID de l'utilisateur à partir de la route

        return [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:utilisateurs,email,' . $userId,
            'telephone' => 'required|string|unique:utilisateurs,telephone,' . $userId,
            'password' => 'required|string|min:8',
            'photo' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
