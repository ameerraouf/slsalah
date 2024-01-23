<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'company_name',
        'description',
        'industry',
        'funding_amount',
        'offer',
        'user_id',
        'rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoriteOpportunities()
    {
        return $this->hasMany(FavoriteOpportunity::class);
    }

    public function investmentPortfolios()
    {
        return $this->hasMany(InvestmentPortfolio::class);
    }
}
