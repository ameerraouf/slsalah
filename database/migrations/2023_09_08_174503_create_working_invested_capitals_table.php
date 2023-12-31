<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingInvestedCapitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_invested_capitals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('workspace_id');
            $table->string('investing_description');
            $table->double('investing_monthly_cost')->default(0);
            $table->double('investing_annual_cost')->default(0);
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
        Schema::dropIfExists('working_invested_capitals');
    }
}
