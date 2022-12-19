<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('supervisors')->user()->can('students::add');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (!function_exists('mb_str_word_count')) {
            function mb_str_word_count($string, $format = 0, $charlist = '[]')
            {
                $string = trim($string);
                if (empty($string))
                    $words = array();
                else
                    $words = preg_split('~[^\p{L}\p{N}\']+~u', $string);
                switch ($format) {
                    case 0:
                        return count($words);
                        break;
                    case 1:
                    case 2:
                        return $words;
                        break;
                    default:
                        return $words;
                        break;
                }
            }
        }
        return [
            'name' => ["required", function ($attribute, $value, $fail) {
                if (mb_str_word_count($value) < 4) {
                    $fail('The ' . $attribute . ' must be at least 4 words. ');
                }
            },],
            'university_id' => 'required|numeric|unique:students,university_id',
            'level' => '',
            'department' => '',
            'batch' => '',
            'fees_id' => 'required|exists:fees,id',
            'installments_id' => 'required|exists:installments,id',
            'reductions_id' => 'required|exists:reductions,id',
            'is_active' => 'required',
        ];
    }
}
