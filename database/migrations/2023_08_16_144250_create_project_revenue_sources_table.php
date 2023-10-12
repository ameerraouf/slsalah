<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectRevenueSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_revenue_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('project_revenue_planning_id');
            $table->string('unit')->nullable();
            $table->string('unit_price')->nullable();
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
        Schema::dropIfExists('project_revenue_sources');
    }
}
