<?php

namespace App\Http\Requests\Reductions;

use Illuminate\Foundation\Http\FormRequest;

class StoreReductionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('supervisors')->user()->can('reductions::add');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => "required|unique:fees,name",
            'amount' => 'required|numeric|min:1.0',
            'is_percentage' => 'required|boolean',
        ];
    }
}
