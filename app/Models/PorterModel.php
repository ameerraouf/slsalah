<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PorterModel extends Model
{
    use HasFactory;
    protected $fillable = [
        "uuid",
        "company_name",
        "rivals",
        "customers",
        "entrants",
        "suppliers",
        "substitute",
        "admin_id",
        "workspace_id"
    ];
}
