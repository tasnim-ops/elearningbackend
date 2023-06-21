<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class VisioconferenceRequest extends FormRequest
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
            'conf_title' => 'required|string',
            'conf_description' => 'required|string',
            'teacher_id' => 'required|exists:teachers,id',
            'conf_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $currentDate = date('Y-m-d');
                    if ($value < $currentDate) {
                        $fail('La date doit être supérieure ou égale à la date actuelle.');
                    }
                },
            ],
            'conf_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $currentDate = date('Y-m-d');
                    $currentTime = date('H:i');
                    $selectedDate = $this->input('conf_date');

                    if ($selectedDate == $currentDate && $value <= $currentTime) {
                        $fail('L\'heure doit être supérieure à l\'heure actuelle.');
                    }
                },
            ],
            'status' => ['required', Rule::in(['to do', 'done'])],
        ];
    }


}
