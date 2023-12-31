<?php

namespace App\Http\Requests\ProjectPlanning;

use Illuminate\Foundation\Http\FormRequest;

class newProjectRevenuePlanningRequest extends FormRequest
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
            'name'=>'required|string|max:255|unique:project_revenue_plannings,name',
//            'yearly_increasing_percentage'=>'required|numeric|min:0|max:100',
            'source_name'=>'sometimes|array',
            'source_name.*'=>'required|string|max:255',
            'unit'=>'sometimes|array',
            'unit.*'=>'required|numeric|gt:0',
            'unit_price'=>'sometimes|array',
            'unit_price.*'=>'required|numeric|gt:0',
        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'يرجى اضافة اسم الإيراد الرئيسي',
            'name.unique'=>'يوجد نفس اسم الإيراد الرئيسي مكرر مسبقا',
//            'yearly_increasing_percentage.required'=>'اضف نسبة الزيادة السنوية',
//            'yearly_increasing_percentage.min'=>'نسبة الزيادة السنوية من 0 الى 100',
//            'yearly_increasing_percentage.max'=>'نسبة الزيادة السنوية من 0 الى 100',
            'source_name.*.required'=>'يرجى اضافة اسم الإيراد الفرعي ',
            'source_name.*.max'=>'اسم الإيراد الفرعي  يجب ان يكون اقل من 255 حرف',
            'unit.*.required'=>'يرجى اضافة وحدة الإيراد الفرعي',
            'unit.*.max'=>' عدد عدد الإيراد الفرعي يجب ان يكون اقل من 255',
            'unit.*.min'=>'عدد الايراد لا يجب ان يكون اقل من 0',
            'unit.*.numeric'=>'وحدة الإيراد الفرعي يجب ان تكون رقم',
            'unit_price.*.required'=>'يرجى اضافة سعر  الايراد الفرعي',
            'unit_price.*.numeric'=>'سعر  الايراد الفرعي يجب ان تكون رقم',
            'unit_price.*.min'=>'سعر  الايراد الفرعي لا يجب ان يكون اقل من 0',

        ];
    }
}
