<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use MongoDB\BSON\Regex;

class RoleRequest extends FormRequest
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
            'name' => ['required','string','unique:roles,name',
                'regex:/[a-zA-Z]+$/'
            ],

        ];
    }
    public function messages(): array
    {
        return [
            'name.regex'=>'The role name must be only alphabets'
        ];
    }
}
