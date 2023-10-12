<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningCostAssumption extends Model
{
    use HasFactory;
    protected $fillable = ['workspace_id','operational_costs','general_expenses','marketing_expenses'];
}
