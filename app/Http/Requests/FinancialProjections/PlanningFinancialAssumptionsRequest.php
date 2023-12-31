<?php

namespace App\Http\Requests\FinancialProjections;

use App\Models\PlanningCostAssumption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PlanningFinancialAssumptionsRequest extends FormRequest
{
    public $max_net_profit;
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
        $this->max_net_profit = 0;
        $planningCostAssumption = PlanningCostAssumption::where(['workspace_id' =>Auth::user()->workspace_id])->first();
        if($planningCostAssumption){
            $this->max_net_profit = 100 - ($planningCostAssumption->operational_costs + $planningCostAssumption->general_expenses + $planningCostAssumption->marketing_expenses);
        }
        return [
            'net_profit'=>'required|numeric|min:'.$this->max_net_profit.'|max:'.$this->max_net_profit,
            'cash_percentage_of_net_profit'=>'required|numeric|min:0|max:99.99'
        ];
    }
    public function messages()
    {
        return [
            'net_profit.required'=>'يرجى اضافة صافي الربح',
            'net_profit.numeric'=>'يرجى اضافة قيمة عددية لصافي الربح',
            'net_profit.min'=>' هي اقل قيمة عددية لصافي الربح'.$this->max_net_profit,
            'net_profit.max'=>' هي أكبر قيمة عددية لصافي الربح'.$this->max_net_profit,
            'cash_percentage_of_net_profit.required'=>'يرجى اضافة نسبة النقد من صافي الربح',
            'cash_percentage_of_net_profit.numeric'=>'يرجى اضافة قيمة عددية لنسبة النقد من صافي الربح',
            'cash_percentage_of_net_profit.min'=>'0 هي اقل قيمة عددية لنسبة النقد من صافي الربح',
            'cash_percentage_of_net_profit.max'=>'99.99 هي أكبر قيمة عددية لنسبة النقد من صافي الربح',
        ];
    }
}
