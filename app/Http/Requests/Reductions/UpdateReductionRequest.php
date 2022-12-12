<?php

namespace App\Http\Requests\Reductions;

use App\Models\Fee;
use App\Models\Reduction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class UpdateReductionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('supervisors')->user()->can('reductions::update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $nameRules = 'unique:reductions,name';
        $fee = Reduction::find(Route::getCurrentRoute()->parameter('id'));

        if ($fee and request()->has('name')) {
            if ($fee->name == request()->input('name')) {
                $nameRules = '';
            }
        }
        return [
            'name' => $nameRules,
            'amount' => 'numeric|min:1.0',
            'is_percentage' => 'boolean',
        ];
    }
}
