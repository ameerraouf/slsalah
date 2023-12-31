<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('account_type', [1, 2])->nullable()->after('email');
            $table->string('company_name')->nullable()->after('account_type');
            $table->unsignedInteger('count_startup_company')->nullable()->after('company_name');
            $table->string('from')->nullable()->after('count_startup_company');
            $table->string('to')->nullable()->after('from');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_type', 'company_name', 'count_startup_company', 'from', 'to']);
        });
    }

}
