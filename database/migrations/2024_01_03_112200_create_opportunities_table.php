<?php
// in create_opportunities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('company_name');
            $table->text('description');
            $table->string('industry');
            $table->decimal('funding_amount', 10, 2);
            $table->text('offer');
            $table->unsignedBigInteger('user_id'); // Added user_id
            $table->integer('rate')->nullable(); // Added rate
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('opportunities');
    }
}
