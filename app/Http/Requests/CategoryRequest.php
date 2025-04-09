<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;


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
            'categories' => 'nullable|array',
            'categories.*' => 'string|exists:categories,name',
            'new_categories' => 'nullable|array',
            'new_categories.*' => ['distinct','required_without:categories',
                function ($attribute, $value, $fail) {
                    if (Category::where('disabled','yes')->where('name', $value)->exists()) {
                        $fail("Category '{$value}' is disabled.");
                    }
                }
                ],
            'date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'categories.*.exists' => 'The selected category does not exist.',
            'new_categories.*.distinct' => 'Duplicate new categories are not allowed.',
            'new_categories.*.required_without' => 'If no category is selected, you must enter at least one new category.',
        ];
    }



    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $existing = $this->input('categories', []);
            $new = $this->input('new_categories', []);

            $normalizedExisting = array_map(function ($v) {
                return strtolower(trim($v));
            }, $existing);

            foreach ($new as $item) {
                if (!is_string($item)) continue;

                $normalized = strtolower(trim($item));
                if (in_array($normalized, $normalizedExisting)) {
                    $validator->errors()->add('error1', "The new category '{$item}' is already selected.");
                }
            }
        });
    }



}
