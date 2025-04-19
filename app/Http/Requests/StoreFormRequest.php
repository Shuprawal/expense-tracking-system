<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
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
           'category' => ['required', 'array'],
//           'category' => ['required'],
            'percentage' => ['required', 'array'],
//            'percentage' => ['required'],
            'percentage.*' => ['integer', 'min:0', 'max:100'],
        ];
    }
    public function messages(): array
    {
        return [
            'percentage.*.min' => 'This cant be less that zero',
            'percentage.*.max' => 'percentage cannot be more than 100',
            'percentage.*.integer' => 'percentage must be an integer',
        ];
    }
    public function withValidator($validator)
    {

        $validator->after(function ($validator) {
            $selectedDate = session('categoryDate');
            $start=Carbon::parse($selectedDate)->startOfMonth();
            $end=Carbon::parse($selectedDate)->endOfMonth();
            $user = auth()->user();
            $exitingPercentage = $user->categories()
                ->whereBetween('category_user.date', [$start, $end])
                ->sum('category_user.percentage');
            $percentages = $this->input('percentage', []);
            if (!is_array($percentages)) {
                return;
            }
            $newPercentage = array_sum($percentages);
            if ($newPercentage > 100) {
                $validator->errors()->add('percentage', 'The total percentage must not exceed 100%.');
            }
            $totalPercentage = $newPercentage + $exitingPercentage;
            if ($totalPercentage > 100) {
                $validator->errors()->add('percentage', 'The total percentage must not exceed 100% as existing percentage is .' .$exitingPercentage );

            }


        });
    }
}
