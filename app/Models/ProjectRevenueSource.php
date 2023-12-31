<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRevenueSource extends Model
{
    use HasFactory;
    protected $fillable = ['name','project_revenue_planning_id','unit','unit_price'];

    public function getTotalRevenueAttribute(){
        return $this->unit * $this->unit_price;
    }
    public function getTotalSecondRevenueAttribute(){
        return $this->total_revenue + ($this->projectRevenue->yearly_increasing_percentage/100 * $this->total_revenue);
    }
    public function getTotalThirdRevenueAttribute(){
        return $this->total_second_revenue + ($this->projectRevenue->yearly_increasing_percentage/100 * $this->total_second_revenue);
    }


    public function projectRevenue(){
        return $this->belongsTo(ProjectRevenuePlanning::class, 'project_revenue_planning_id', 'id');
    }
}
