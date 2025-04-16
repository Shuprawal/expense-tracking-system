<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0|regex:/^\d{1,16}(\.\d{1,4})?$/',
            'description' => 'required|string',
            'date' => 'required|date|before:tomorrow',
        ];
    }
    public function messages(): array
    {
        return [
            'amount.regex'=>'income amount limit reached',
        ];
    }
}
