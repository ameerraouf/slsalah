<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Investor extends Model
{
    use HasFactory;


    public static function getTotalInvestment($workspace_id){

        $fixedSum = DB::table('fixed_invested_capitals')
            ->select('investing_price')
            ->where(['workspace_id' => $workspace_id])->sum('investing_price');
        $workingSum = DB::table('working_invested_capitals')
            ->select('investing_annual_cost')
            ->where(['workspace_id' => $workspace_id])->sum('investing_annual_cost');
        return $fixedSum + $workingSum;
    }
}
