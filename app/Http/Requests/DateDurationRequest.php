<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DateDurationRequest extends FormRequest
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
            'start' => 'date|before:end',
            'end' => 'date|after:start',
        ];
    }
    public function messages(): array
    {
        return [

            'start.before' => 'Start date must be before end date',
            'end.after' => 'End date must be after start date',
        ];
    }
}
