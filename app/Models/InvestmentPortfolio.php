<?php

// in InvestmentPortfolio.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentPortfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opportunity_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }
}
