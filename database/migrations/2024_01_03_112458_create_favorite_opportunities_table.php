<?php
// في الملف create_favorite_opportunities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteOpportunitiesTable extends Migration
{
    public function up()
    {
        Schema::create('favorite_opportunities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('opportunity_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('opportunity_id')->references('id')->on('opportunities');
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorite_opportunities');
    }
}
