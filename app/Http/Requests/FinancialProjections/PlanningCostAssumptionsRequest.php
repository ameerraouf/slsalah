<?php

namespace App\Http\Requests\FinancialProjections;

use Illuminate\Foundation\Http\FormRequest;

class PlanningCostAssumptionsRequest extends FormRequest
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
            'operational_costs'=>'required|numeric|min:1',
            'general_expenses'=>'required|numeric',
            'marketing_expenses'=>'required|numeric'
        ];
    }
    public function messages()
    {
        return [
            'operational_costs.required'=>'يرجى اضافة التكاليف التشغيلية',
            'general_expenses.required'=>'يرجى اضافة المصروفات عمومية',
            'marketing_expenses.required'=>'يرجى اضافة المصروفات تسويقية',
            'operational_costs.numeric'=>'التكاليف التشغيلية من نوع رقم',
            'general_expenses.numeric'=>'المصروفات عمومية من نوع رقم',
            'marketing_expenses.numeric'=>'المصروفات تسويقية من نوع رقم',
            'operational_costs.min'=>'يرجى اضافة قيمة أكبر من او تساوي 1 للتكاليف التشغيلية',
            'general_expenses.min'=>'يرجى اضافة قيمة أكبر من او تساوي 5 للمصروفات عمومية',
            'marketing_expenses.min'=>'يرجى اضافة قيمة أكبر من او تساوي 5 للمصروفات تسويقية',
            'general_expenses.max'=>'يرجى اضافة قيمة أقل من او تساوي 20 للمصروفات عمومية',
            'marketing_expenses.max'=>'يرجى اضافة قيمة أقل من او تساوي 20 للمصروفات تسويقية',
        ];
    }
}
