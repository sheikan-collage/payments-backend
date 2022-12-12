<?php

namespace App\Http\Requests\Installments;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstallmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('supervisors')->user()->can('installments::add');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => "required|unique:installments,name",
            'divisions' => ['required', 'array', 'min:2',         function ($attribute, $value, $fail) {

                if (array_sum($value) !== 100) {
                    $fail('The ' . $attribute . ' summation must equal 100.');
                }
            },],
            'divisions.*' => 'required|integer|min:1'
        ];
    }
}
