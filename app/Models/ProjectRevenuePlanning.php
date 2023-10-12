<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

class ProjectRevenuePlanning extends Model
{
    use HasFactory;
    static $row_id;
    protected $fillable = ['name','yearly_increasing_percentage','workspace_id','main_unit_price','main_unit'];
    public function sources(){
        return $this->hasMany(ProjectRevenueSource::class,'project_revenue_planning_id');
    }
    public function getProjectRevenuePlanningDisplayDataAttribute(){
        return [
            'id'=>SELF::$row_id,
            'name'=>$this->name,
            'sourcesCount'=>$this->sources->count(),
            'sources'=>$this->sources_table,
            'yearly_increasing_percentage'=>$this->yearly_increasing_percentage,
            'tools'=>$this->edit.'&nbsp'.$this->delete
        ];
    }
    public function getSourcesTableAttribute(){
        $sources = $this->sources;
        $table = '';
        if($sources->count()){
            $table = '<table class="table table-bordered"><thead><th>'.$this->name.'</th><th>'.__('source_unit').'</th><th>'.__('source_unit_price').'</th></thead>';
            foreach ($sources as $source){
                $table .= '<tr>
                               <td>'.$source->name.'</td>
                               <td>'.$source->unit.'</td>
                               <td>'.$source->unit_price.'</td>
                            </tr>';
            }
            $table .= '</table>';
        }
        return $table;
    }

    public static function calcTotalRevenueFirstYear(){
        $workspace_id = Auth::user()->workspace_id;
        $total = 0;
        $revenus = ProjectRevenuePlanning::where(['workspace_id' => $workspace_id])->get();
        foreach ($revenus as $revenu){
            foreach ($revenu->sources as $source){
                $total += $source->total_revenue;
            }
        }
        return $total;
    }
    public static function calcTotalRevenueSecondYear(){
        $workspace_id = Auth::user()->workspace_id;
        $total = 0;
        $revenus = ProjectRevenuePlanning::where(['workspace_id' => $workspace_id])->get();
        foreach ($revenus as $revenu){
            foreach ($revenu->sources as $source){
                $total += $source->total_second_revenue;
            }
        }
        return $total;
    }
    public static function calcTotalRevenueThirdYear(){
        $workspace_id = Auth::user()->workspace_id;
        $total = 0;
        $revenus = ProjectRevenuePlanning::where(['workspace_id' => $workspace_id])->get();
        foreach ($revenus as $revenu){
            foreach ($revenu->sources as $source){
                $total += $source->total_third_revenue;
            }
        }
        return $total;
    }

    public function calcMainFirstYear(){
        return $this->main_unit * $this->main_unit_price;
    }
    public function calcMainSecondYear(){
        return $this->calcMainFirstYear() + ($this->yearly_increasing_percentage/100 * $this->calcMainFirstYear());
    }
    public function calcMainThirdYear(){
        return $this->calcMainSecondYear() + ($this->yearly_increasing_percentage/100 * $this->calcMainSecondYear());
    }


    public function scopeSearch($builder,$word){
        if(empty($word)){
            return $builder;
        }else{
            return $builder->where('name','like','%'.$word.'%')
                ->orWhereHas('sources',function ($query) use($word) {
                    return $query->where('name','like','%'.$word.'%')
                        ->orWhere('unit','like','%'.$word.'%');
                });
        }
    }





    public function getEditAttribute(){
        return '<button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" rel="tooltip" title="Edit '.$this->name.'" onclick="getUpdateForm(\''.route('project-revenue-planning.edit',$this->id).'\',this)"><i class="fa fa-edit"></i></button>';
    }
    public function getDeleteAttribute(){
        return '<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" rel="tooltip" title="Delete '.$this->name.'" onclick="deleteItem(\''.route('project-revenue-planning.destroy',$this->id).'\')"><i class="fa fa-trash"></i></button>';
    }
    public static function boot(){
        parent::boot();

        static::deleting(function($plan) {
            $plan->sources()->delete();
        });
    }
}
