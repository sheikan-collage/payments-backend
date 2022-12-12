<?php

namespace App\Http\Requests\Installments;

use App\Models\Fee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class UpdateInstallmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('supervisors')->user()->can('installments::update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $nameRules = 'unique:installments,name';
        $fee = Fee::find(Route::getCurrentRoute()->parameter('id'));

        if ($fee and request()->has('name')) {
            if ($fee->name == request()->input('name')) {
                $nameRules = '';
            }
        }
        return [
            'name' => $nameRules,
            'divisions' => ['array', 'min:2', function ($attribute, $value, $fail) {
                
                if (!is_array($value) || array_sum($value) !== 100) {
                    return $fail('The ' . $attribute . ' summation must equal 100.');
                }
            },],
            'divisions.*' => 'integer|min:1'
        ];
    }
}
