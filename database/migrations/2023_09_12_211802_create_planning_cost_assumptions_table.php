<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningCostAssumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planning_cost_assumptions', function (Blueprint $table) {
            $table->id();
            $table->integer('workspace_id');
            $table->string('operational_costs')->default(0);
            $table->string('general_expenses')->default(0);
            $table->string('marketing_expenses')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planning_cost_assumptions');
    }
}
