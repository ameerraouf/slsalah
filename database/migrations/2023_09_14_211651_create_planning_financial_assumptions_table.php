<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningFinancialAssumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planning_financial_assumptions', function (Blueprint $table) {
            $table->id();
            $table->string('net_profit')->default(0);
            $table->string('cash_percentage_of_net_profit')->default(0);
            $table->string('workspace_id')->default(0);
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
        Schema::dropIfExists('planning_financial_assumptions');
    }
}
