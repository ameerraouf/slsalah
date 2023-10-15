<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('subscription_plan_id');
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans');
            $table->string('subscription_type')->nullable();
            $table->string('price')->nullable();
            $table->string('payment_type')->nullable();
            $table->date('subscription_date_start');
            $table->date('subscription_date_end');
            $table->boolean('is_subscription_end')->default(0);
            $table->string('bank_name')->nullable();
            $table->string('image_bank_transfer')->nullable();
            $table->string('number_of_transfer')->nullable();
            $table->boolean('is_active')->default(1);
            $table->text('reason_of_not_work')->nullable();
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
        Schema::dropIfExists('subscribes');
    }
}
