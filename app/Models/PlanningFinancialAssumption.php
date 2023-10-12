<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningFinancialAssumption extends Model
{
    use HasFactory;
    protected $fillable = ['net_profit','cash_percentage_of_net_profit','workspace_id'];
}
