<?php

namespace App\Http\Controllers;

use App\Models\FixedInvestedCapital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestingCapitalPlanning extends BaseController
{
    //

    public function index(){
        $workspaceId = $this->user->workspace_id;

        $fixedSum = DB::table('fixed_invested_capitals')
            ->select('investing_price')
            ->where(['workspace_id' => $workspaceId])->sum('investing_price');
        $workingSum = DB::table('working_invested_capitals')
            ->select('investing_annual_cost')
            ->where(['workspace_id' => $workspaceId])->sum('investing_annual_cost');

        return \view("investingCapitalPlanning.list", [
            "selected_navigation" => "invested_capital_planning",
            "investingSum" =>  ($fixedSum + $workingSum),

        ]);

    }

}
