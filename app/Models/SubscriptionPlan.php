<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    public static function availableModules()
    {
        return [
            [
                'title' =>'1- '. __('marketing_and_economic_plan'),
                'modules' => [
                    'projects' => __('Product Planning'),
                    'swot' => __('SWOT Analysis'),
                    'porter' => __('نموذج porter 5'),
                    'pestle' => __('PESTLE Analysis'),
                    'project_revenue_planning' => __('project_revenue_planning'),
                ],
            ],
            [
                'title' =>'2 -'. __('Managing the strategic business plan'),
                'modules' => [
                    'brainstorm' => __('Ideation Canvas'),
                    'business_model' => __('Business Model'),
                    'to_dos' => __('Tasks'),
                    'mckinsey' => __('McKinsey 7-S Model'),
                    'calendar' => __('Calendar'),
                    'notes' => __('Note Book'),
//                    'documents' => __('Documents'),
                ],
            ],
            [
                'title' =>'3 -'. __('invested capital plan'),
                'modules' => [
                    'fixed_capital_planning' => __('Fixed capital planning'),
                    'working_capital_planning' => __('Working capital planning'),
                    'investors' => __('Investors_plan'),
                ],
            ],
            [
                'title' =>'4 -'. __('financial projections'),
                'modules' => [
                    'planning_cost_assumptions' => __('Planning cost assumptions'),
                    'planning_financial_assumptions' => __('Planning financial assumptions'),
                    'planning_revenue_operating_assumptions' => __('Planning revenue operating assumptions'),
                ],
            ],
            [
                'title' =>'5 -'. __('financial reports'),
                'modules' => [
                    'PlanningRevenueOperatingAssumptions' => 'التقرير المالي الشامل',
                    'IncomeList' => "فيديوهات تعليمية حول دراسة المشاريع وإعداد الخطط اللازمة <br>باستخدام الأدوات العلمية اللازمة.",
                    'statement_of_cash_flows' => "فيديوهات تعليمية حول التخطيط المالي للمشاريع باستخدام<br>الأدوات العلمية اللازمة. ",
                    'capital_investment_model' => "جلسات دعم ومساعدة من قبل مختصين واستشاريين في مجال تخطيط<br> وتنفيذ المشاريع (جلستين – 3 جلسات - 4 جلسات).",
                ],
            ],
//            'startup_canvas' => __('Startup Canvas'),
//            'pest' => __('PEST Analysis'),
//            'business_plan' => __('Business Plan'),
//            'marketing_plan' => __('Marketing Plan'),
        ];
    }
    public static function availableModulesOriginal()
    {
        return [
            'projects' => __('Product Planning'),
            'to_dos' => __('Tasks'),
            'brainstorm' => __('Ideation Canvas'),
            'investors' => __('Investors'),
            'business_model' => __('Business Model'),
            'startup_canvas' => __('Startup Canvas'),
            'swot' => __('SWOT Analysis'),
            'pest' => __('PEST Analysis'),
            'pestle' => __('PESTLE Analysis'),
            'business_plan' => __('Business Plan'),
            'marketing_plan' => __('Marketing Plan'),
            'calendar' => __('Calendar'),
            'notes' => __('Note Book'),
            'documents' => __('Documents'),
            'mckinsey' => __('McKinsey 7-S Model'),
            'porter' => __('Porter\'s Five Forces Model'),
            'project_revenue_planning' => __('project_revenue_planning'),
            'fixed_capital_planning' => __('project_revenue_planning'),
            'working_capital_planning' => __('project_revenue_planning'),
            'planning_cost_assumptions' => __('project_revenue_planning'),
            'planning_financial_assumptions' => __('project_revenue_planning'),
            'planning_revenue_operating_assumptions' => __('project_revenue_planning'),
            'PlanningRevenueOperatingAssumptions' => __('project_revenue_planning'),
            'IncomeList' => __('project_revenue_planning'),
            'statement_of_cash_flows' => __('project_revenue_planning'),
            'capital_investment_model' => __('project_revenue_planning'),
            'invested_capital_plan' => __('invested capital plan'),
            'textReport' => __('invested capital plan'),
            'financial_projections' => __('financial projections'),
            'financial_reports' => __('financial reports'),
        ];
    }

    public function workspace(){
        return $this->hasMany(Workspace::class, 'plan_id', 'id');
    }

}
