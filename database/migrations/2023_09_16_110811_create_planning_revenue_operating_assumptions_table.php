<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningRevenueOperatingAssumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planning_revenue_operating_assumptions', function (Blueprint $table) {
            $table->id();
            $table->integer('workspace_id')->default('0');
            $table->string('first_year')->default('50');
            $table->string('second_year')->default('100');
            $table->string('third_year')->default('100');
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
        Schema::dropIfExists('planning_revenue_operating_assumptions');
    }
}
