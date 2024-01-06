<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PestelAnalysis extends Model
{
    use HasFactory;
    protected $fillable = [
        "uuid",
        "company_name",
        "political",
        "economic",
        "social",
        "technological",
        "environmental",
        "legal",
        "admin_id",
        "workspace_id"
    ];
}
