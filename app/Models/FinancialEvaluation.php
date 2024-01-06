<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialEvaluation extends Model
{
    use HasFactory;
    protected $table = 'finanical_evaluations';
    protected $fillable = ['workspace_id' , 'value'];
}
