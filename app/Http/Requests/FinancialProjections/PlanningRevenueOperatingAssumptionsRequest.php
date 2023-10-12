<?php

namespace App\Http\Requests\FinancialProjections;

use Illuminate\Foundation\Http\FormRequest;

class PlanningRevenueOperatingAssumptionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_year'=>'required|numeric|min:50',
            'second_year'=>'required|numeric|min:100',
            'third_year'=>'required|numeric|min:100',
        ];
    }
    public function messages()
    {
        return [
            'first_year.required'=>'يرجى اضافة نسبة السنة الاولى',
            'first_year.numeric'=>'نسبة السنة الاولى قيمة عددية',
            'first_year.min'=>'أقل قيمة للسنة الاولى اكبر من او تساوي 50 %',
            'second_year.required'=>'يرجى اضافة نسبة السنة الثانية',
            'second_year.numeric'=>'نسبة السنة الثانية قيمة عددية',
            'second_year.min'=>'أقل قيمة للسنة الثانية اكبر من او تساوي 100 %',
            'third_year.required'=>'يرجى اضافة نسبة السنة الثالثة',
            'third_year.numeric'=>'نسبة السنة الثالثة قيمة عددية',
            'third_year.min'=>'أقل قيمة للسنة الثالثة اكبر من او تساوي 100 %',
        ];
    }
}
