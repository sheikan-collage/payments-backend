<?php

namespace App\Http\Requests\Students;

use App\Models\Fee;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('supervisors')->user()->can('students::update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $universityIdUniquenessRule = '|unique:students,university_id';
        $student = Student::find(Route::getCurrentRoute()->parameter('id'));

        if ($student and request()->has('university_id')) {
            if ($student->university_id == request()->input('university_id')) {
                $universityIdUniquenessRule = '';
            }
        }
        return [
            'name' => [function ($attribute, $value, $fail) {
                if (str_word_count($value) < 4) {
                    $fail('The ' . $attribute . ' must be at least 4 words.');
                }
            },],
            'university_id' => 'numeric' . $universityIdUniquenessRule,
            'level' => '',
            'department' => '',
            'batch' => '',
            'fees_id' => 'exists:fees,id',
            'installments_id' => 'exists:installments,id',
            'reductions_id' => 'exists:reductions,id',
            'is_active' => '',
        ];
    }
}
