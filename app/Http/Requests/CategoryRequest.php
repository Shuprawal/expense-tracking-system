<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'selected_categories' => 'array',
            'selected_categories.*' => 'string|exists:categories,name',
            'new_categories' => 'array',
            'new_categories.*' => 'string|unique:categories,name',
            'date' =>'date|required'
        ];

//        return [
//            'selected_categories'   => 'array',
//            'selected_categories.*' => 'string|exists:categories,name',
//            'new_categories'        => 'array',
//            'new_categories.*'      => 'string|unique:categories,name|min:2',
//            'date'                  => 'required|date',
//        ];
    }
}
