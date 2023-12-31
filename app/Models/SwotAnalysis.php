<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwotAnalysis extends Model
{
    use HasFactory;
    protected $fillable = [
        "uuid",
        "company_name",
        "strengths",
        "weaknesses",
        "threats",
        "opportunities",
        "admin_id",
        "workspace_id"
    ];
}
